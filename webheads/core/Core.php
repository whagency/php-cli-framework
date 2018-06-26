<?php

namespace webheads\core;

use League\CLImate\CLImate;

class Core
{
    public $protocol;
    public $exception = false;
    public $cli = null;

	public function __construct()
    {
        $this->getError();
 
        if (WEB_APP) {
            if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
                $this->protocol = '1.0';
            } else {
                $this->protocol = '1.1';
            }
        } else {
            $this->cli = new CLImate();
        }
    }

    function getError() {

        ini_set('display_errors', false);
        error_reporting(0);

        set_exception_handler(function ($e) {

            $code = $e->getCode();
            $code = !empty($code)?$code:500;
            $this->exception = true;

            Log::errorMsg(
                [
                    'Exception:' => $e->getMessage(),
                    'Code:' => $code,
                    'File:' => $e->getFile(),
                    'Line:' => $e->getLine()
                ], 
                $e->getMessage(),
                $this->cli
            );
            
            if (WEB_APP) {
                header("HTTP/{$this->protocol} {$code} {$e->getMessage()}");
            }

            // Log::add('Error '.$e->getCode().': '.$e->getMessage());
        });

        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== NULL && !$this->exception) {

                Log::errorMsg(
                    [
                        'Error 500:' => $error["message"],
                        'Error level:' => $this->getErrorType($error["type"]),
                        'File:' => $error["file"],
                        'Line:' => $error["line"]
                    ], 
                    'Internal Server Error.',
                    $this->cli
                );

                if (WEB_APP) {
                    header("HTTP/{$this->protocol} 500 Internal Server Error");
                }

                // Log::add('Error 500: '.$error["message"]);

            } else {
                echo $this->exception ? '' : NL;
            }

            if (!WEB_APP) {

                $this->cli->table([
                    [
                        '<info><bold>Server time</bold></info>',
                        round(microtime(true) - START_TIME, 5).' sec.',
                        '-'
                    ],
                    [
                        '<info><bold>Memory</bold></info>',
                        round((memory_get_usage() / 1048576), 5).' MB',
                        ini_get('memory_limit')
                    ]
                ]);

            } else {
                // echo microtime(true) - START_TIME;
            }
        });
    }

    function getErrorType($type) 
    { 
        switch($type) 
        { 
            case E_ERROR: // 1 // 
                return 'E_ERROR'; 
            case E_WARNING: // 2 // 
                return 'E_WARNING'; 
            case E_PARSE: // 4 // 
                return 'E_PARSE';
            case E_NOTICE: // 8 // 
                return 'E_NOTICE'; 
            case E_CORE_ERROR: // 16 // 
                return 'E_CORE_ERROR'; 
            case E_CORE_WARNING: // 32 // 
                return 'E_CORE_WARNING'; 
            case E_COMPILE_ERROR: // 64 // 
                return 'E_COMPILE_ERROR'; 
            case E_COMPILE_WARNING: // 128 // 
                return 'E_COMPILE_WARNING'; 
            case E_USER_ERROR: // 256 // 
                return 'E_USER_ERROR'; 
            case E_USER_WARNING: // 512 // 
                return 'E_USER_WARNING'; 
            case E_USER_NOTICE: // 1024 // 
                return 'E_USER_NOTICE'; 
            case E_STRICT: // 2048 // 
                return 'E_STRICT'; 
            case E_RECOVERABLE_ERROR: // 4096 // 
                return 'E_RECOVERABLE_ERROR'; 
            case E_DEPRECATED: // 8192 // 
                return 'E_DEPRECATED'; 
            case E_USER_DEPRECATED: // 16384 // 
                return 'E_USER_DEPRECATED'; 
        } 

        return ""; 
    }

    function getUrl() 
    { 
        $answer = [
            'module' => '',
            'class' => ''
        ];
        $url = trim(trim(URL), '/');
        $parts = explode('/', $url);
        $module = isset($parts[0])?$parts[0]:'';
        $action = isset($parts[1])?$parts[1]:'';
        if (empty($module) && empty($action) && DEBUG) {
            Log::startMsg($this->cli);
        } else if (empty($action) && in_array($module, ['add', 'info']) && !WEB_APP) {
            $class_ns = '\webheads\core\{class}';
            $class_ns = str_replace('{class}', ucfirst($module), $class_ns);
            $answer['class'] = $class_ns;
        } else {
            if (empty($module) || !is_dir(WH_PATH.'/modules/'.$module)) {
                throw new CoreException(404, 'Page not found.');
            } else {
                $answer['module'] = $module;
                if (!empty($action)) {
                    $url_parts = explode('-', trim($action, '/'));
                    $class = '';
                    foreach ($url_parts as $k => $v) {
                        $class .= ucfirst($v);
                    }
                    if (!empty($class) && file_exists(WH_PATH.'/modules/'.$module.'/'.$class.'.php')) {
                        $class_ns = '\webheads\modules\{module}\{class}';
                        $class_ns = str_replace('{module}', $module, $class_ns);
                        $class_ns = str_replace('{class}', $class, $class_ns);
                        $answer['class'] = $class_ns;
                    } else {
                        throw new CoreException(404, 'Page not found.');
                    }
                } else {
                    throw new CoreException(404, 'Page not found.');
                }
            }
        }

        return $answer;
    }

    public function start()
    {
        $route = $this->getUrl();
        $module = new $route['class'];
    	$module->run();
    }
}
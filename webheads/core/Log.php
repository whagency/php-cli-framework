<?php

namespace webheads\core;

use League\CLImate\CLImate;

class Log
{
    public static function add($msg = '')
    {
    	if (!empty($msg)) {
    		$msg = date('d.m.Y H:i:s').'  -  '.$msg."\n";
    		error_log($msg, 3, TMP_PATH.'/core.log');
    	}
    }

    public static function clear()
    {
    	file_put_contents(TMP_PATH.'/core.log', '');
    }

    public static function errorMsg($msg_debug = [], $msg_prod = '', $cli = null)
    {
        if (DEBUG) {
            echo NL;
            foreach ($msg_debug as $k => $v) {
                if (WEB_APP) {
                    echo $k.' '.$v.NL;
                } else {
                    $cli->red()->inline($k)->inline(' '.$v.NL);
                }
            }
        } else {
            echo $msg_prod.NL;
        }
    } 

    public static function startMsg($cli = null)
    {
        if (!WEB_APP) {
            $cli->bold('CLI CORE '.VERSION.'. DUBUG MODE.')->br();
            $padding = $cli->padding(20);
            $padding->label('<comment>php core info</comment> ')->result('list of all available modules');
            $padding->label('<comment>php core add</comment> ')->result('add new module');
        } else {
            echo '<h4>CLI CORE '.VERSION.'. DUBUG MODE.</h4>';
            echo '<b>php core info</b> - list of all available modules'.NL;
            echo '<b>php core add</b> - add new module';
        }
        exit;        
    }  

    public static function die($class_name = '')
    {
        $cli = new CLImate();
        $parts = explode('\\modules\\', $class_name);
        $name = isset($parts[1])?$parts[1]:$class_name;
        $name = trim($name, '\\');
        $cli->backgroundRed()->inline('Locked. Module: '.$name);
        die();
    }    
}
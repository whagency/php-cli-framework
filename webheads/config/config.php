<?php

if (php_sapi_name() === 'cli') {
	$root_path = getenv('PWD');
	$url = !empty($_SERVER['argv'][1])?$_SERVER['argv'][1]:'';
	$web_app = false;
	$nl = "\n";
} else {
	$root_path = $_SERVER['DOCUMENT_ROOT'];
	$url = $_SERVER['REQUEST_URI'];
	$web_app = true;
	$nl = '<br>';
}

defined('DEBUG') || define('DEBUG', false);
defined('VERSION') || define('VERSION', '1.0.1');
defined('START_TIME') || define('START_TIME', microtime(true));
defined('WH_PATH') || define('WH_PATH', $root_path.'/webheads');
defined('FILES_PATH') || define('FILES_PATH', $root_path.'/files');
defined('TMP_PATH') || define('TMP_PATH', $root_path.'/tmp');
defined('URL') || define('URL', $url);
defined('WEB_APP') || define('WEB_APP', $web_app);
defined('NL') || define('NL', $nl);
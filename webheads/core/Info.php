<?php

namespace webheads\core;

use League\CLImate\CLImate;

class Info
{
	public function __construct()
    {
    }

    public function run() {
    	$cli = new CLImate();
    	$padding = $cli->padding(50);
        if ($handle = opendir(WH_PATH.'/modules')) {
            while (false !== ($file = readdir($handle))) {
                if (!in_array($file, [".", "..", ".DS_Store"])) {
                	if (is_dir(WH_PATH.'/modules/'.$file)) {
                		$cli->bold()->yellow()->flank($file);
	                	if ($handle2 = opendir(WH_PATH.'/modules/'.$file)) {
	                    	while (false !== ($file2 = readdir($handle2))) {
	                    		if (!in_array($file2, ["config", ".", "..", ".DS_Store"])) {
	                    			$file2 = preg_replace('/.php$/', '', $file2);
	                    			$pass1 = preg_replace("/([a-z])([A-Z])/", "\\1 \\2", $file2);
									$pass2 = preg_replace("/([A-Z])([A-Z][a-z])/", "\\1 \\2", $pass1);

									$action_url = trim($pass2);
									$action_url = str_replace(' ', '-', $action_url);
									$action_url = strtolower($action_url);
									$config = [];
									if (file_exists(WH_PATH.'/modules/'.$file.'/config/config.php')) {
										$config = require(WH_PATH.'/modules/'.$file.'/config/config.php');
									}
									$descr = !empty($config['descr'][$file2])?$config['descr'][$file2]:'';
									$cli->tab();
									if (!empty($descr)) {
										$padding->label('php core '.$file.'/'.$action_url.' ')->result($descr);
									} else {
										$cli->out('php core '.$file.'/'.$action_url.' ');
									}
	                    		}
	                    	}
	                    	closedir($handle2);
	                    }
                	}
                }
            }
            closedir($handle);
        }
    }
}
<?php

namespace webheads\core;

use League\CLImate\CLImate;

class Add
{
	public function __construct()
    {
    }

    public function run() {
    	      
        $cli = new CLImate();
        $input = $cli->input("<green>Enter module's name [only letters or numbers]:</green> ");
        $input->accept(function($response) use ($cli) {
        	$response = preg_replace('/[^A-Za-z0-9]/', '', $response);
		    if (!empty($response)) {
		    	return true;
		    } else {
		    	$cli->red("Module's name cannot be blank and must contain only letters or numbers.");
		    }
		});
		$module_name = $input->prompt();
		$module_name = preg_replace('/[^A-Za-z0-9]/', '', $module_name);
		$module_name = strtolower($module_name);
		$module_name = trim($module_name);

        $input = $cli->input("<green>Enter actions's name [only letters]:</green> ");
        $input->accept(function($response) use ($cli) {
        	$response = preg_replace('/[^A-Za-z]/', '', $response);
		    if (!empty($response)) {
		    	return true;
		    } else {
		    	$cli->red("Actions's name cannot be blank and must contain only letters.");
		    }
		});
		$action_name = $input->prompt();
		$action_name = preg_replace('/[^A-Za-z]/', '', $action_name);
		$action_name = strtolower($action_name);
		$action_name = trim($action_name);
		$action_name = ucfirst($action_name);

        $db_hostname = '';
        $db_database = '';
        $db_username = '';
        $db_password = '';

        if (!is_dir(WH_PATH.'/modules/'.$module_name.'/config')) {

            $input = $cli->input("<green>Include MySQL options [no]:</green> ");
            $input->accept(function($response) use ($cli) {
                $response = trim($response);
                if (empty($response) || $response == 'yes') {
                    return true;
                } else {
                    $cli->red("Type 'yes' or 'no'");
                }
            });
            $include_db = $input->prompt();
            if ($include_db == 'yes') {

                $input = $cli->input("<green>Enter MySQL hostname:</green> ");
                $input->accept(function($response) use ($cli) {
                    $response = preg_replace('/[^A-Za-z0-9.-]/', '', $response);
                    if (!empty($response)) {
                        return true;
                    } else {
                        $cli->red("Enter correct MySQL hostname.");
                    }
                });
                $db_hostname = $input->prompt();

                $input = $cli->input("<green>Enter MySQL database:</green> ");
                $input->accept(function($response) use ($cli) {
                    $response = preg_replace('/[^A-Za-z0-9.-]/', '', $response);
                    if (!empty($response)) {
                        return true;
                    } else {
                        $cli->red("Enter correct MySQL database.");
                    }
                });
                $db_database = $input->prompt();

                $input = $cli->input("<green>Enter MySQL username:</green> ");
                $input->accept(function($response) use ($cli) {
                    $response = trim($response);
                    if (!empty($response)) {
                        return true;
                    } else {
                        $cli->red("Enter correct MySQL username.");
                    }
                });
                $db_username = $input->prompt();

                $input = $cli->password("<green>Enter MySQL password:</green> ");
                $input->accept(function($response) use ($cli) {
                    $response = trim($response);
                    if (!empty($response)) {
                        return true;
                    } else {
                        $cli->red("Enter correct MySQL password.");
                    }
                });
                $db_password = $input->prompt();

            }

        }

    	ob_start();
        include(WH_PATH.'/core/generators/Module.php');
        $result_module = ob_get_contents();
        ob_end_clean();

        ob_start();
        include(WH_PATH.'/core/generators/Config.php');
        $result_config = ob_get_contents();
        ob_end_clean();

        if (!is_dir(WH_PATH.'/modules/'.$module_name)) {
        	mkdir(WH_PATH.'/modules/'.$module_name, 0775);
        }

        if (!file_exists(WH_PATH.'/modules/'.$module_name.'/'.$action_name.'.php')) {
        	file_put_contents(WH_PATH.'/modules/'.$module_name.'/'.$action_name.'.php', $result_module);
        }

        if (!is_dir(WH_PATH.'/modules/'.$module_name.'/config')) {
        	mkdir(WH_PATH.'/modules/'.$module_name.'/config', 0775);
        	file_put_contents(WH_PATH.'/modules/'.$module_name.'/config/config.php', $result_config);
        }

        $cli->backgroundBlue()->out('Success! The module was created and is accessible by <bold>php core '.$module_name.'/'.strtolower($action_name).'</bold>');
    }
}
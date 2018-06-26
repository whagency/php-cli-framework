<?php

namespace webheads\core;

use ActiveRecord\Config;
use ActiveRecord\ConnectionManager;

class Module
{
	public $config = [];
	public $conn = [];

	public function __construct()
    {
    	$this->dbConnnect();
    }

    public function dbConnnect() {
		if (!empty($this->config['conn']) && is_array($this->config['conn'])) {
			$connections = [];
			foreach ($this->config['conn'] as $k => $v) {
				if (!empty($v['hostname'])) {
					$connections[$k] = 'mysql://'.$v['username'].':'.$v['password'].'@'.$v['hostname'].'/'.$v['database'].';charset='.$v['charset'];
				}
			}

			Config::initialize(function($cfg) use ($connections)
			{
				$cfg->set_model_directory('.');
				$cfg->set_connections($connections);
			});

			foreach ($connections as $k => $v) {
				$adapter = ConnectionManager::get_connection($k);
				$this->conn[$k] = $adapter;
			}
		}
    }
}
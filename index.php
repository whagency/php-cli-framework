<?php

use webheads\core\Core;

defined('DEBUG') || define('DEBUG', true);

require('webheads/config/config.php');
require(WH_PATH.'/autoload.php');

(new Core())->start();
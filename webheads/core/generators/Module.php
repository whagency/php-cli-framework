<?php

echo "<?php\n";
?>

namespace webheads\modules\<?= $module_name ?>;

use webheads\core\Module;
use webheads\core\Translit;
use webheads\core\Log;
use webheads\core\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Dariuszp\CliProgressBar;

class <?= $action_name ?> extends Module <?= "\n" ?>
{
    public function __construct()
    {
    	$this->config = require(__DIR__ . '/config/config.php');
    	
    	parent::__construct(); 
    }

    public function run()
    {
    	Log::die(static::class);
        
    }
}
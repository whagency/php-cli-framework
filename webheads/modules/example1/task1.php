<?php

namespace webheads\modules\example1;

use webheads\core\Module;
use webheads\core\Translit;
use webheads\core\Log;
use webheads\core\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Dariuszp\CliProgressBar;
use League\CLImate\CLImate;
use webheads\core\ExcelReadFilter;
use Tinify;

class Task1 extends Module 
{
    public function __construct()
    {
    	$this->config = require(__DIR__ . '/config/config.php');
    	
    	parent::__construct(); 
    }

    public function run()
    {
        $cli = new CLImate();
        $cli->br()->comment('EXAMPLE! File: '.__FILE__.'. Look the code for more information.');
        die;


        // die if not CLI
        if (WEB_APP) {
            Log::die(static::class);
        }


        // all databases connections added from config
        $db = $this->conn['db'];
        $db2 = $this->conn['db2'];


        // example of downloading files with progress bar using information from remote database
        $query = "
            select id, filename 
            from files f 
            where type = :type
        ";
        $params = [':type' => 1];
        $data = $db->query($query, $params)->fetchAll();
        $bar = new CliProgressBar(count($data));
        $bar->display();
        foreach ($data as $k => $v) {
            $file = File::load('http://example.com.ua/prodphoto/'.$v['filename'], FILES_PATH.'/example');
            $bar->progress();
        }


        // example of import excel to remote databse
        $inputFileName = FILES_PATH . '/example/test.xlsx';
        $inputFileType = IOFactory::identify($inputFileName);
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $data = $spreadsheet->getSheet(1)->toArray(null, true, true, true);
        $bar = new CliProgressBar(count($data));
        $bar->display();
        foreach ($data as $k => $v) {
            $query = "update mytable set price = :price, currency = :currency where id = :id";
            $params = [
                ':id' => $v['A'],
                ':price' => $v['B'],
                ':currency' => $v['C']
            ];
            $db->query($query, $params);
            $bar->progress();
        }


        // example of export excel to pdf
        $reader = new Xlsx();
        $reader->setReadFilter(new ExcelReadFilter(1, 29, range('A', 'G'))); // read a part of excel file
        $spreadsheet = $reader->load(FILES_PATH . '/example/test.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
        $writer->save(FILES_PATH . '/example/test.pdf');


        // example of recursive getting all images in directory and all subdirectories and optimize them with TinyPng API
        $data = File::getInFolder(FILES_PATH.'/example/images', [
            'images' => true, // false - all files types, true - images only
            'tree' => false // true - output with tree structure, false - output with simple files list
        ]);
        foreach ($data as $k => $v) {
            Tinify\setKey("YOUR_API_KEY");
            $source = Tinify\fromFile($v);
            $name = Translit::t(basename($v));
            $source->toFile(FILES_PATH.'/example/images/tinypng/'.$name);
        }
    }
}
<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

require_once 'app/Mage.php';

Mage::init('admin');

/** @var Danslo_ApiImport_Model_Import_Api $api */
//$api = Mage::getModel('api_import/import_api');

if ($handle = opendir('var/importexport/products')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $files[] =$entry;
        }
    }
    closedir($handle);
}
array_multisort($files, SORT_NATURAL);

    foreach ($files as $_file){
        printf("\n ---  mem usage: %d   ---\n",memory_get_usage());
        /** @var $import Danslo_ApiImport_Model_Import_Api */
        $import = Mage::getModel('api_import/import_api');
        $file = fopen('var/importexport/products/' . $_file, 'r');
        printf("Importing: %s \n", $_file);
        $entities = array();
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            print_r($header);
            print_r($row);
            $entities[] = array_combine($header, $row);
            var_dump($entities);
            die();
        }
        try {
            //var_dump($entities);
            $import->importEntities($entities);
            fclose($file);
        }catch (Mage_Core_Exception $e) {
            printf("%s: %s\n", $e->getMessage(), $e->getCustomMessage());
        }
    }


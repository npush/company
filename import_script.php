<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

require_once 'app/Mage.php';

Mage::init('admin');

/** @var Danslo_ApiImport_Model_Import_Api $api */
//$api = Mage::getModel('api_import/import_api');

if ($handle = opendir('var/importexport/products_small')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $files[] =$entry;
        }
    }
    closedir($handle);
}
array_multisort($files, SORT_NATURAL);

try {
    foreach ($files as $_file){
        $api = Mage::getModel('api_import/import_api');

        $file = fopen('var/importexport/products_small/' . $_file, 'r');
        printf("Importing: %s \n", $_file);
        $entities = array();
        print_r($entities);
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            $entities[] = array_combine($header, $row);
        }
        $api->importEntities($entities);
        fclose($file);
        unset($api);
    }
} catch (Mage_Api_Exception $e) {
    printf("%s: %s\n", $e->getMessage(), $e->getCustomMessage());
}

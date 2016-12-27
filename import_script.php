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

try {
    foreach ($files as $_file){
        $file = fopen('var/importexport/products/' . $_file, 'r');
        printf("Importing: %s \n", $_file);
        $entities = array();
        $header = fgetcsv($file);
        while ($row = fgetcsv($file)) {
            $entities[] = array_combine($header, $row);
        }
        Mage::getModel('api_import/import_api')->importEntities($entities);
        fclose($file);
    }
} catch (Mage_Api_Exception $e) {
    printf("%s: %s\n", $e->getMessage(), $e->getCustomMessage());
}

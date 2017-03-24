<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/6/16
 * Time: 11:50 AM
 */

require_once 'abstract.php';

class Mage_Shell_ProductManualImport extends Mage_Shell_Abstract{

    const PRODUCT_SKU = 0;
    const MANUAL_FILE_NAME = 1;
    const MANUAL_FILE_LABEL = 2;
    const MANUAL_FILE_DESCRIPTION = 3;
    const MANUAL_ATTRIBUTE_CODE = 'file_upload';

    protected $_manualDir;

    /**
     * Run script
     *
     */
    public function run(){
        $this->_manualDir =  Mage::getBaseDir('media') . '/import/manual/';
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {

                    //$this->setAttributeValue($data);
                    $this->saveManual($data);
                    echo "Adding to " . $data[self::PRODUCT_SKU] . "\n";
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    public function saveManual($_data){
        $product_sku = $_data[self::PRODUCT_SKU];
        $productId = Mage::getModel('catalog/product')->getIdBySku($product_sku);
        if($productId){
            $_files = explode(';', $_data[self::MANUAL_FILE_NAME]);
            foreach($_files as $_file){
                $fileName = $this->uploadFile($_file, Mage::getBaseDir('media') . '/catalog/product_manual');
                $manualModel = Mage::getModel('user_manual/manual');
                $data = [
                    'entity_id' => $productId,
                    'value' => $fileName,
                    'store_id' => (int)Mage::app()->getStore(true)->getId(),
                    'label' => $_data[self::MANUAL_FILE_LABEL],
                    'description' => $_data[self::MANUAL_FILE_DESCRIPTION],
                ];
                $manualModel->setData($data);
                $manualModel->save();

                printf("%s --> %s\n", $_data[self::PRODUCT_SKU], $_data[self::MANUAL_FILE_LABEL]);
            }
        }else{
            echo 'Product' . $_data[self::PRODUCT_SKU]  . "not found\n";
        }
    }

    public function setAttributeValue($_data){
        $product = Mage::getModel('catalog/product');
        if($productId = $product->getIdBySku($_data[self::PRODUCT_SKU])) {
            $fileName = $this->uploadFile($_data[self::MANUAL_FILE_NAME], Mage::getBaseDir('media') . '/catalog/product_manual');
            $product->load($productId);
            //$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$_data[self::PRODUCT_SKU]);
            $product->setData(self::MANUAL_ATTRIBUTE_CODE, $fileName)
                ->getResource()
                ->saveAttribute($product, self::MANUAL_ATTRIBUTE_CODE);
            printf("%s --> %s\n", $_data[self::PRODUCT_SKU], $_data[self::MANUAL_FILE_LABEL]);
        }else{
    	    echo 'Product' . $_data[1]  . "not found\n";
        }
    }

    public function uploadFile($fileName, $destinationFolder){
        $this->_createDestinationFolder($destinationFolder);
        $destinationFile = $destinationFolder;
        $fileName = $this->getCorrectFileName($fileName);
        $dispretionPath = $this->getDispretionPath($fileName);
        $destinationFile .= $dispretionPath;

        $this->_createDestinationFolder($destinationFile);

        $destinationFile = $this->_addDirSeparator($destinationFile) . $fileName;
        if(copy($this->_manualDir . $fileName, $destinationFile)){
            chmod($destinationFile, 0666);
            $fileName = str_replace(DIRECTORY_SEPARATOR, '/',
                    $this->_addDirSeparator($dispretionPath)) . $fileName;
        }
        return $fileName;
    }

    public function getCorrectFileName($fileName)
    {
        $fileName = preg_replace('/[^a-z0-9_\\-\\.]+/i', '_', $fileName);
        $fileInfo = pathinfo($fileName);

        if (preg_match('/^_+$/', $fileInfo['filename'])) {
            $fileName = 'file.' . $fileInfo['extension'];
        }
        return $fileName;
    }

    private function _createDestinationFolder($destinationFolder)
    {
        if (!$destinationFolder) {
            return ;
        }

        if (substr($destinationFolder, -1) == DIRECTORY_SEPARATOR) {
            $destinationFolder = substr($destinationFolder, 0, -1);
        }

        if (!(@is_dir($destinationFolder) || @mkdir($destinationFolder, 0777, true))) {
            throw new Exception("Unable to create directory '{$destinationFolder}'.");
        }
    }

    public function getDispretionPath($fileName){
        $char = 0;
        $dispretionPath = '';
        while (($char < 2) && ($char < strlen($fileName))) {
            if (empty($dispretionPath)) {
                $dispretionPath = DIRECTORY_SEPARATOR
                    . ('.' == $fileName[$char] ? '_' : $fileName[$char]);
            } else {
                $dispretionPath = $this->_addDirSeparator($dispretionPath)
                    . ('.' == $fileName[$char] ? '_' : $fileName[$char]);
            }
            $char ++;
        }
        return $dispretionPath;
    }

    protected function _addDirSeparator($dir){
        if (substr($dir,-1) != DIRECTORY_SEPARATOR) {
            $dir.= DIRECTORY_SEPARATOR;
        }
        return $dir;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f attributeSetImport.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_ProductManualImport();
$shell->run();
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/9/17
 * Time: 2:51 PM
 */

require_once 'abstract.php';

class Mage_Shell_ProductTooltipsImport extends Mage_Shell_Abstract{

    const PRODUCT_SKU = 1;
    const TOOLTIP_FILE_NAME = 2;

    const TOOLTIP_ID = 0;
    const TOOLTIP_LABEL = 1;
    const TOOLTIP_DESCRIPTION = 2;
    const TOOLTIP_FILE = 3;

    protected $_tooltipsDir;

    /**
     * Run script
     *
     */
    public function run(){
        $this->_tooltipsDir =  Mage::getBaseDir('media') . '/import/tooltips/';
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {

                    //$this->setAttributeValue($data);
                    $this->addTooltip($data);
                    echo "Adding to " . $data[self::PRODUCT_SKU] . "\n";
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    public function addTooltip($_data){
        $_files = explode(';', $_data[self::TOOLTIP_FILE]);
        foreach($_files as $_file){
            $fileName = $this->uploadFile($_file, Mage::getBaseDir('media') . '/tooltips');
            $tooltipsModel = Mage::getModel('product_tooltips/tooltip');
            $data = [
                'tooltip_id' => $_data[self::TOOLTIP_ID],
                'title' => $_data[self::TOOLTIP_LABEL],
                'description' => $_data[self::TOOLTIP_DESCRIPTION] == '\N' ? '' : $_data[self::TOOLTIP_DESCRIPTION],
                'image_file' => $fileName,
                'created_at' => Varien_Date::now(),
                'status' => 1
            ];
            $tooltipsModel->setData($data);
            $tooltipsModel->save();

            printf("%s --> %s\n", $_data[self::TOOLTIP_ID], $_data[self::TOOLTIP_LABEL]);
        }
    }

    public function addRelation($_data){

    }

    public function uploadFile($fileName, $destinationFolder){
        $this->_createDestinationFolder($destinationFolder);
        $destinationFile = $destinationFolder;
        $fileName = $this->getCorrectFileName($fileName);
        $dispretionPath = $this->getDispretionPath($fileName);
        $destinationFile .= $dispretionPath;

        $this->_createDestinationFolder($destinationFile);

        $destinationFile = $this->_addDirSeparator($destinationFile) . $fileName;
        if(copy($this->_tooltipsDir . $fileName, $destinationFile)){
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
Usage:  php -f productTooltipsImport.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_ProductTooltipsImport();
$shell->run();
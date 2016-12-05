<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/25/16
 * Time: 12:10 PM
 */
class Mage_Shell_fileUploader{
    protected $_sourceDir;

    public function uploadFile($fileName, $destinationFolder, $sourceDir){
        $this->_sourceDir = $sourceDir;
        $this->_createDestinationFolder($destinationFolder);
        $destinationFile = $destinationFolder;
        $fileName = $this->getCorrectFileName($fileName);
        $dispretionPath = $this->getDispretionPath($fileName);
        $destinationFile .= $dispretionPath;

        $this->_createDestinationFolder($destinationFile);

        $destinationFile = $this->_addDirSeparator($destinationFile) . $fileName;
        if(copy($this->_sourceDir . $fileName, $destinationFile)){
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
     * @param $dir string directory relative media dir.
     */
    public function setSourceDir($dir){
        $this->_sourceDir = Mage::getBaseDir('media') . DS . $dir;
    }
}
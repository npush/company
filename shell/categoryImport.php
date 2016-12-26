<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/16
 * Time: 4:14 PM
 */

require_once 'abstract.php';


class Mage_Shell_CategoryImport extends Mage_Shell_Abstract{

    protected $_attributeSetId;
    protected $_lastParent;
    protected $position = 0;

    /**
     * Run script
     *
     */
    public function run(){
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    $this->createCategory($data);
                }
                fclose($file);
                //$this->_getChildCount($data);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    protected function _getChildCount($data){
        foreach($data as $_category) {
            $categories = explode('/', $_category[2]);
            }
    }

    protected function _getAttributeSetId()
    {
        if (is_null($this->_attributeSetId)) {
            $this->_attributeSetId = Mage::getModel('eav/entity_type')->load('catalog/category', 'entity_model')->getDefaultAttributeSetId();
        }
        return $this->_attributeSetId;
    }

    protected function createCategory($data)
    {
        $_levelCount = count(explode('/', $data[2]));
        if(!$this->_lastParent){
            $this->_lastParent = explode('/', $data[2])[$_levelCount - 2];
        }

        $_parent = explode('/', $data[2])[$_levelCount - 2];
        if($_parent == $this->_lastParent) {
            $this->position++;
        }else{
            $this->position = 1;
        }
        $this->_lastParent = $_parent;
        echo  explode('/', $data[2])[$_levelCount - 2] . PHP_EOL;
        try{
            $category = Mage::getModel('catalog/category');
            $category->setId($data[0]);
            $category->setName($data[3]);
            $category->setDescription($data[4]);
            $category->setPath($data[2]);
            $category->setPosition($this->position);
            $category->setLevel($_levelCount - 1);
            $category->setIsActive(1);
            $category->setDisplayMode('PRODUCTS');
            $category->setIsAnchor(1); //for active achor
            $category->setIncludeInMenu(1);
            $category->setStoreId(Mage::app()->getStore()->getId());
            $category->setAttributeSetId($this->_getAttributeSetId());
            $category->setCreatedAt(Varien_Date::now());
            $category->save();
        } catch(Exception $e) {
            var_dump($e);
        }

        $category = Mage::getModel('catalog/category')->load($data[0]);
        echo $category->getId() ? $category->getId() : '⚡';

        /*$category = Mage::getModel('catalog/category');
        $category->setId($data[0]);
        $category->setParentId($data[1]);
        $category->setAttributeSetId($this->_getAttributeSetId());
        $category->setUrlPath($data[3]);
        $category->setUrlKey($data[4]);
        $category->setPath($data[5]);
        $category->setPosition($data[6]);
        $category->setPageLayout($data[7]);
        $category->setDescription($data[8]);
        $category->setDisplayMode($data[9]);
        $category->setIsActive($data[10]);
        $category->setIsAnchor($data[11]);
        $category->setIncludeInMenu($data[12]);
        $category->setCustomDesign($data[13]);
        $category->setLevel($data[14]);
        $category->setName($data[15]);
        $category->setMetaTitle($data[16]);
        $category->setMetaKeywords($data[17]);
        $category->setMetaDescription($data[18]);
        $category->save();
        $category = Mage::getModel('catalog/category')->load($data[0]);
        echo $category->getId() ? $category->getId() : '⚡';*/
        echo PHP_EOL;
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f importCategories.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_Categoryimport();
$shell->run();
<?php

class Stableflow_Pricelists_Model_Pricelist extends Mage_Core_Model_Abstract {

    protected $pathToFile = 'pricelists/';

    protected function _construct() {
        $this->_init('pricelists/pricelist');
    }

    public function getLettersRange() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'N', 'O', 'P', 'Q',
            'R', 'S', 'T', 'U', 'V', 'W',
            'X', 'Y', 'Z'
        );
    }

    public static function getTypes(){
        return array('name', 'price', 'code', 'manufacturer');
    }

    public function getConfig() {
        return unserialize($this->configurations);
    }

    public function setConfig($configuration) {
        $this->configurations = serialize($configuration);
    }

    public function getRow() {
        $config = $this->getConfig();
        return $config['row'];
    }

    public function getMapping() {
        $config = $this->getConfig();
        return $config['mapping'];
    }

    public function getPathToFile() {
        return $this->pathToFile . $this->filename .".xls";
    }
}

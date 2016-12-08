<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/8/16
 * Time: 3:30 PM
 */
class Cybage_Marketplace_Model_Company extends Mage_Catalog_Model_Abstract {

    public function _construct(){
        parent::_construct();
        $this->_init('marketplace/company');
    }
}
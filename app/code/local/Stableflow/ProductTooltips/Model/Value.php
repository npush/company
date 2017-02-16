<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/16/17
 * Time: 5:33 PM
 */
class Stableflow_ProductTooltips_Model_Value extends Mage_Core_Model_Abstract
{


    public function _construct(){
        parent::_construct();
        $this->_init('product_tooltips/value_value');
    }
}
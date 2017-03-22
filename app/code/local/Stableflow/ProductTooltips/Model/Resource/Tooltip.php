<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/8/17
 * Time: 3:22 PM
 */
class Stableflow_ProductTooltips_Model_Resource_Tooltip extends Mage_Core_Model_Resource_Db_Abstract{

    protected $_isPkAutoIncrement = false;

    /**
     *
     */
    public function _construct(){
        $this->_init('product_tooltips/tooltip', 'tooltip_id');
    }

}
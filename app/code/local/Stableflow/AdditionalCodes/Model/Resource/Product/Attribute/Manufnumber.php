<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/16/17
 * Time: 4:54 PM
 */

class Stableflow_AdditionalCodes_Model_Resource_Product_Attribute_Manufnumber extends Mage_Eav_Model_Resource_Entity_Attribute
{
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('additional_codes/product_attribute_manufacturer_number', 'value_id');
    }

}
<?php

class Stableflow_AdditionalCodes_Model_Resource_Product_Attribute_Manufnumber_Collection
    extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * Resource model initialization
     *
     */
    protected function _construct()
    {
        $this->_init('additional_codes/product_attribute_manufnumber');
    }
}
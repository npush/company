<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 6:08 PM
 */

class Stableflow_AdditionalCodes_Model_Product_Attribute_Frontend_Codes extends Mage_Eav_Model_Entity_Attribute_Frontend_Abstract{

    public function getValue(Varien_Object $object){
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        return 'demo';
    }

    public function getOption($optionId)
    {
        return "fdsf";
    }

}
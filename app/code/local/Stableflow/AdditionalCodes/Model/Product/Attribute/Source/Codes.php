<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 6:22 PM
 */
class Stableflow_AdditionalCodes_Model_Product_Attribute_Source_Codes extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options[] = array(
                'label' => Mage::helper('core')->__('-- Please Select --'),
                'value'
            );
        }
        return $this->_options;
    }

    public function getOptionText($value){
        return 'asdasdasd';
    }


}

<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/17/17
 * Time: 6:16 PM
 */

class Stableflow_Company_Model_Company_Attribute_Source_Company extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{


    public function getAllOptions(){
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('company')->__('Demo comp'),
                    'value' =>  '1',
                ),
            );
        }
        return $this->_options;
    }

    public function toOptionArray(){
        return $this->getAllOptions();
    }

    public function getOptionArray()
    {
        return array(
            '1' => Mage::helper('company')->__('Demo comp'),
        );
    }

}
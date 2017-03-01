<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 12:17 PM
 */
class Stableflow_Company_Model_Company_Attribute_Source_Status extends  Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    const APPROVE = 1;
    const PENDING = 2;
    const DISABLE = 3;

    public function getAllOptions(){
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('company')->__('Approve'),
                    'value' =>  self::APPROVE,
                ),
                array(
                    'label' => Mage::helper('company')->__('Pending'),
                    'value' =>  self::PENDING,
                ),
                array(
                    'label' => Mage::helper('company')->__('Disable'),
                    'value' =>  self::DISABLE,
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
            self::APPROVE => Mage::helper('company')->__('Approve'),
            self::PENDING => Mage::helper('company')->__('Pending'),
            self::DISABLE => Mage::helper('company')->__('Disable'),
        );
    }
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/12/16
 * Time: 1:15 PM
 */
class Stableflow_Company_Model_Company_Attribute_Source_Activity extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    const LIGHTS = 1;
    const ELECTRIC = 2;
    const SECURITY = 3;
    const CONSTRUCTION = 4;
    const OTHER = 5;

    public function getAllOptions(){
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('company')->__('Lights'),
                    'value' =>  self::LIGHTS,
                ),
                array(
                    'label' => Mage::helper('company')->__('Electric'),
                    'value' =>  self::ELECTRIC,
                ),
                array(
                    'label' => Mage::helper('company')->__('Security'),
                    'value' =>  self::SECURITY,
                ),
                array(
                    'label' => Mage::helper('company')->__('Construction'),
                    'value' =>  self::CONSTRUCTION,
                ),
                array(
                    'label' => Mage::helper('company')->__('Other'),
                    'value' =>  self::OTHER,
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
            self::LIGHTS        => Mage::helper('company')->__('Lights'),
            self::ELECTRIC      => Mage::helper('company')->__('Electric'),
            self::SECURITY      => Mage::helper('company')->__('Security'),
            self::CONSTRUCTION  => Mage::helper('company')->__('Construction'),
            self::OTHER         => Mage::helper('company')->__('Other')
        );
    }

}
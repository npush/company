<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/12/16
 * Time: 1:16 PM
 */
class Stableflow_Company_Model_Company_Attribute_Source_Type extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    const SELLER = 1;
    const PRODUCER = 2;
    const CORPORATION = 3;
    const SHOP = 4;
    const ENTREPRENEUR = 5;
    const MIXED = 6;

    public function getAllOptions(){
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => Mage::helper('company')->__('Seller'),
                    'value' =>  self::SELLER,
                ),
                array(
                    'label' => Mage::helper('company')->__('Producer'),
                    'value' =>  self::PRODUCER,
                ),
                array(
                    'label' => Mage::helper('company')->__('Corporation'),
                    'value' =>  self::CORPORATION,
                ),
                array(
                    'label' => Mage::helper('company')->__('Shop'),
                    'value' =>  self::SHOP,
                ),
                array(
                    'label' => Mage::helper('company')->__('Entrepreneur'),
                    'value' =>  self::ENTREPRENEUR,
                ),
                array(
                    'label' => Mage::helper('company')->__('Mixed'),
                    'value' =>  self::MIXED,
                ),

            );
        }
        return $this->_options;
    }

    public function toOptionArray(){
        return $this->getAllOptions();
    }
}
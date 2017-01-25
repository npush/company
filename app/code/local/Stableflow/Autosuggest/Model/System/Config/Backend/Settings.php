<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/25/17
 * Time: 1:06 PM
 */
class Stableflow_Autosuggest_Model_System_Config_Backend_Settings{


    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Product Name')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Thumb Image')),
            array('value' => 3, 'label' => Mage::helper('adminhtml')->__('Short Description')),
            array('value' => 4, 'label' => Mage::helper('adminhtml')->__('Price'))
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            1 => Mage::helper('adminhtml')->__('Product Name'),
            2 => Mage::helper('adminhtml')->__('Thumb Image'),
            3 => Mage::helper('adminhtml')->__('Short Description'),
            4 => Mage::helper('adminhtml')->__('Price'),
        );
    }
}
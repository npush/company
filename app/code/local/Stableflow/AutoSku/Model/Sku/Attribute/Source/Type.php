<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/13/17
 * Time: 11:49 AM
 */
class Stableflow_AutoSku_Model_Sku_Attribute_Source_Type
{
    public function toOptionArray()
    {
        return array(
            array('value' => 2, 'label'=>Mage::helper('adminhtml')->__('Category Id and Product ID')),
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Custom SKU')),
        );
    }
}
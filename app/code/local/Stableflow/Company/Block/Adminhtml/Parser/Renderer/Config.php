<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 5:03 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_Config extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        //$html = parent::render($row);
        $html = '<button onclick="editConfigurationField('. $row->getId() .'); return false">' . Mage::helper('company')->__('Edit Config') . '</button>';
        return $html;
    }
}
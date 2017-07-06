<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/6/17
 * Time: 11:51 AM
 */
class Stableflow_BlackIp_Block_Adminhtml_Widget_Grid_Column_Renderer_Inline extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Input
{
    public function render(Varien_Object $row)
    {
        $html = parent::render($row);

        $html .= '<button onclick="updateField(this, '. $row->getId() .'); return false">' . Mage::helper('sf_blackip')->__('Update') . '</button>';

        return $html;
    }

}
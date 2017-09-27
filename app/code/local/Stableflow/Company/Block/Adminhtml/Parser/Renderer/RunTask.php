<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/27/17
 * Time: 2:53 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_RunTask
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        //$html = parent::render($row);
        $html = '<button onclick="runTaskImmediately('.$row->getId().'); return false">'.
            Mage::helper('company')->__('Run Task').'</button>';
        return $html;
    }
}
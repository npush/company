<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/28/17
 * Time: 1:26 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_ParsedRow
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $html = $row->getLastRow();
        return $html ? $html : 'Not parsed';
    }
}
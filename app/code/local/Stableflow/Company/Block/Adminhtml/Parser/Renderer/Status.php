<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 6:04 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{


    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        if($value) {
            return '<span style="color: red;">' . $value . '</span>';
        }
        return $value;
    }
}
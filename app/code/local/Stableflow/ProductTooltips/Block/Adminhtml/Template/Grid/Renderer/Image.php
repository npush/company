<?php




class Stableflow_ProductTooltips_Block_Adminhtml_Template_Grid_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $val = Mage::app()->getStore()->getBaseUrl('media').'tooltips' . $row->getValue();
        $out = "<img src=". $val ." width='97px'/>";
        return $out;
    }
}
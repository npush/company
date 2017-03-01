<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/22/16
 * Time: 6:25 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Helper_Image extends Varien_Data_Form_Element_Image
{
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('company/image')->getImageBaseUrl().
                $this->getValue();
        }
        return $url;
    }
}
<?php

class Stableflow_Pricelists_Block_Adminhtml_Edit_Renderer_Preview extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface
{

    public function __construct()
    {
        $this->setTemplate('stableflow/pricelists/pricelist.phtml');
        parent::__construct();
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->toHtml();
    }

    public function urlToFile() {
        /** @var Stableflow_Pricelists_Model_Pricelist $pricelist */
        $pricelist = Mage::registry('current_pricelist');
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $pricelist->getPathToFile();
    }

}
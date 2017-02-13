<?php
class Stableflow_Pricelists_Block_Pricelist_Content extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('stableflow/pricelists/pricelist/view.phtml');
    }
    
    public function getPricelist()
    {
        return Mage::getModel('stableflow_pricelists/pricelist')->load($this->getPricelistId());
    }
}

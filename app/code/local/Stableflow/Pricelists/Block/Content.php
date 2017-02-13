<?php
class Stableflow_Pricelists_Block_Content extends Mage_Core_Block_Template
{
    protected function _construct()
    {
        $this->setTemplate('stableflow/pricelists/view.phtml');
    }

    public function getRowUrl(Stableflow_Pricelists_Model_Pricelist $pricelist)
    {
        return $this->getUrl('*/*/view', array(
            'id' => $pricelist->getId()
        ));
    }

    public function getCollection()
    {
        return Mage::getModel('stableflow_pricelists/pricelist')->getCollection();
    }
}

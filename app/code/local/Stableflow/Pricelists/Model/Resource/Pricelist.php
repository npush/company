<?php
class Stableflow_Pricelists_Model_Resource_Pricelist extends Mage_Core_Model_Mysql4_Abstract
{
    const STATUS_NOT_APPROVED = 0;
    const STATUS_APPROVED = 1;

    protected function _construct()
    {
        $this->_init('pricelists/pricelist', 'id');
    }

    public function toOptionArray() {
        return array(
            array('value' => self::STATUS_APPROVED, 'label' => 'Approved'),
            array('value' => self::STATUS_NOT_APPROVED, 'label' => 'Not Approved'),
        );
    }

    public function toArray() {
        return array(
            self::STATUS_APPROVED => Mage::helper('stableflow_pricelists')->__('Approved'),
            self::STATUS_NOT_APPROVED => Mage::helper('stableflow_pricelists')->__('Not Approved'),
        );
    }
}

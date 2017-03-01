<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 11:58 AM
 */
class Stableflow_Company_Model_Resource_Address_Attribute_Backend_Street
    extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract {
    /**
     * Prepare object for save
     *
     * @param Varien_Object $object
     * @return Stableflow_Company_Model_Resource_Address_Attribute_Backend_Street
     */
    public function beforeSave($object)
    {
        $street = $object->getStreet(-1);
        if ($street) {
            $object->implodeStreetAddress();
        }
        return $this;
    }
}
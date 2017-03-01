<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 11:48 AM
 */

class Stableflow_Company_Model_Resource_Address_Attribute_Source_Country extends Mage_Eav_Model_Entity_Attribute_Source_Table{

    /**
     * Retreive all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('directory/country_collection')
                ->loadByStore($this->getAttribute()->getStoreId())->toOptionArray();
        }
        return $this->_options;
    }
}
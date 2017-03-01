<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 4:30 PM
 */
class Stableflow_Company_Model_Attribute_Data_Postcode extends Mage_Eav_Model_Attribute_Data_Text{
    /**
     * Validate postal/zip code
     * Return true and skip validation if country zip code is optional
     *
     * @param array|string $value
     * @return boolean|array
     */
    public function validateValue($value)
    {
        $countryId      = $this->getExtractedData('country_id');
        $optionalZip    = Mage::helper('directory')->getCountriesWithOptionalZip();
        if (!in_array($countryId, $optionalZip)) {
            return parent::validateValue($value);
        }
        return true;
    }
}
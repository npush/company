<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 12:03 PM
 */
class Stableflow_Company_Model_Parser_Adapter
{
    /**
     * Adapter factory. Checks for availability, loads and create instance of import adapter object.
     * type ('xls', 'xlsx', 'csv', 'xml' etc.)
     * @param Stableflow_Company_Model_Parser_Config_Settings $settings
     * @param string $file
     * @throws Exception
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    public static function factory($settings, $file)
    {
        $type = $settings->getType();
        if (!is_string($type) || !$type) {
            Mage::throwException(Mage::helper('company')->__('Adapter type must be a non empty. In setting Object'));
        }
        $adapterClass = __CLASS__ . '_' . ucfirst(strtolower($type));

        if (!class_exists($adapterClass)) {
            Mage::throwException("'{$type}' file extension is not supported");
        }
        $adapter = new $adapterClass($settings, $file);

        if (!$adapter instanceof Stableflow_Company_Model_Parser_Adapter_Abstract) {
            Mage::throwException(
                Mage::helper('company')->__('Adapter must be an instance of Stableflow_Company_Model_Parser_Adapter_Abstract')
            );
        }
        return $adapter;
    }
}
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
     *
     * @param string $type Adapter type ('xls, 'csv', 'xml' etc.)
     * @param mixed $options OPTIONAL Adapter constructor options
     * @throws Exception
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    public static function factory($type, $options = null)
    {
        if (!is_string($type) || !$type) {
            Mage::throwException(Mage::helper('company')->__('Adapter type must be a non empty string'));
        }
        $adapterClass = __CLASS__ . '_' . ucfirst(strtolower($type));

        if (!class_exists($adapterClass)) {
            Mage::throwException("'{$type}' file extension is not supported");
        }
        $adapter = new $adapterClass($options);

        if (! $adapter instanceof Stableflow_Company_Model_Parser_Adapter_Abstract) {
            Mage::throwException(
                Mage::helper('company')->__('Adapter must be an instance of Stableflow_Company_Model_Parser_Adapter_Abstract')
            );
        }
        return $adapter;
    }

    /**
     * Create adapter instance for specified source file.
     *
     * @param string $source Source file path.
     * @return Stableflow_Company_Model_Parser_Adapter_Abstract
     */
    public static function findAdapterFor($source)
    {
        return self::factory(pathinfo($source, PATHINFO_EXTENSION), $source);
    }
}
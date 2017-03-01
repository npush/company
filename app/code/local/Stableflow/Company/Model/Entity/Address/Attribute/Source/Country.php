<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 4:32 PM
 */
class Stableflow_Company_Model_Entity_Address_Attribute_Source_Country
    extends Stableflow_Company_Model_Resource_Address_Attribute_Source_Country {
    /**
     * Factory instance
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $_factory;

    /**
     * Constructor for Stableflow_Company_Model_Entity_Address_Attribute_Source_Country
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->_factory = !empty($args['factory']) ? $args['factory'] : Mage::getSingleton('core/factory');
    }
    /**
     * Retrieve all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->_factory->getResourceModel('directory/country_collection')
                ->loadByStore($this->getAttribute()->getStoreId())->toOptionArray();
        }
        return $this->_options;
    }
}
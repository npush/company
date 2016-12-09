<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 3:45 PM
 */
class Stableflow_Company_Model_Attribute extends Mage_Core_Model_Abstract {

    /**
     * Name of the module
     */
    const MODULE_NAME = 'Stableflow_Company';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'company_entity_attribute';

    /**
     * Prefix of model events object
     *
     * @var string
     */
    protected $_eventObject = 'attribute';

    /**
     * Init resource model
     */
    protected function _construct()
    {
        $this->_init('company/attribute');
    }
}
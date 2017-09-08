<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 1:38 PM
 */
class Stableflow_Company_Model_Parser_Price_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{
    const STATUS_DISABLED   = 0;
    const STATUS_ENABLED    = 1;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('company')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('company')->__('Disabled')
        );
    }
}
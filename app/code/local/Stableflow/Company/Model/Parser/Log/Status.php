<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Model_Parser_Log_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{
    const STATUS_ERROR      = 1;
    const STATUS_WARNING    = 2;
    const STATUS_SUCCESS    = 3;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_SUCCESS    => Mage::helper('catalog')->__('SUCCESS'),
            self::STATUS_ERROR      => Mage::helper('catalog')->__('ERROR'),
            self::STATUS_WARNING    => Mage::helper('catalog')->__('WARNING'),
        );
    }

}
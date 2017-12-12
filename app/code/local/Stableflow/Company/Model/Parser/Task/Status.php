<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Model_Parser_Task_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{

    const STATUS_NEW            = 1;
    const STATUS_ERRORS_FOUND   = 2;
    const STATUS_IN_QUEUE       = 3;
    const STATUS_COMPLETE       = 4;
    const STATUS_DISABLED       = 5;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_NEW            => Mage::helper('catalog')->__('New. Not in Queue'),
            self::STATUS_ERRORS_FOUND   => Mage::helper('catalog')->__('Errors Found'),
            self::STATUS_IN_QUEUE       => Mage::helper('catalog')->__('Sent to Queue'),
            self::STATUS_COMPLETE       => Mage::helper('catalog')->__('Complete'),
            self::STATUS_DISABLED       => Mage::helper('catalog')->__('Disabled')
        );
    }
}
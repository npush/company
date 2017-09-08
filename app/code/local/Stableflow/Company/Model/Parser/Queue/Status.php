<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Model_Parser_Queue_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{
    const STATUS_PENDING        = 1;
    const STATUS_IN_PROGRESS    = 2;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_PENDING        => Mage::helper('catalog')->__('Pending'),
            self::STATUS_IN_PROGRESS    => Mage::helper('catalog')->__('In progress...')
        );
    }

    /**
     * Update status value
     *
     * @param   int $queueId
     * @param   int $value
     * @return  Stableflow_Company_Model_Parser_Queue_Status
     */
    public function updateQueueStatus($queueId, $value)
    {
        return $this;
    }
}
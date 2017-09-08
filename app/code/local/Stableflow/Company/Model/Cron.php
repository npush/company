<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/6/17
 * Time: 4:37 PM
 */
class Stableflow_Company_Model_Cron extends Mage_Core_Model_Abstract
{

    public function updatePriceLists()
    {
        Mage::log("Import",null, 'PriceLists.log');
        try{
            $queue = Mage::getModel('company/parser_queue');
            $queue->performQueue();

        }catch (Exception $e){
            Mage::log($e, null, 'PriceLists-exception.log');
        }
    }
}
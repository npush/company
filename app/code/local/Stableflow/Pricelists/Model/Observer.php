<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/21/17
 * Time: 7:15 PM
 */
class Stableflow_Pricelists_Model_Observer extends Mage_Core_Model_Observer{

    public function updatePriceLists(){
        Mage::log("Import",null, 'PriceLists.log');
    }

}
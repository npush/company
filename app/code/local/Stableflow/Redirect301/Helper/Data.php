<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/17/17
 * Time: 12:38 PM
 */
class Stableflow_Redirect301_Helper_Data extends Mage_Core_Helper_Data
{

    public function getRedirectUrl($model, $id){
        return Mage::getModel('core/url_rewrite')
            ->getResource()
            ->getRequestPathByIdPath("$model/$id", Mage::app()->getStore()->getId());
    }
}
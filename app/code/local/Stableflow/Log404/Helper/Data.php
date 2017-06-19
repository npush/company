<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 11:34 AM
 */
class Stableflow_Log404_Helper_Data extends Mage_Core_Helper_Data
{
    const DEFAULT_LOGFILE_NAME = 'log404.log';
    const NOTIFICATION_TYPE_LOG = 0;
    const NOTIFICATION_TYPE_DATABASE = 1;
    const NOTIFICATION_TYPE_COMBINE = 2;

    public function isEnabled()
    {
        $isEnabledSetting = Mage::getStoreConfigFlag('sf_log404/general/is_enabled');
        $isOutputEnabled = !(Mage::getStoreConfigFlag('advanced/modules_disable_output/Stableflow_Log404'));
        $result = ($isEnabledSetting && $isOutputEnabled) ? true : false;
        return $result;
    }

    public function getNotificationType()
    {
        $savedValue = Mage::getStoreConfig('sf_log404/general/notification_type');
        return $savedValue;
    }

    public function getLogfileName()
    {
        $savedValue = Mage::getStoreConfig('sf_log404/general/logfile_name');
        return (empty($savedValue)) ? self::DEFAULT_LOGFILE_NAME : $savedValue;
    }

    public function getStoreId(){
        return Mage::app()->getStore()->getId();
    }

    public function getStoreName(){
        return Mage::app()->getStore()->getName();
    }

    public function log404Error()
    {
        $result = false;
        if ($this->isEnabled()) {
            $notificationType = (int) $this->getNotificationType();
            $requestedUrl = Mage::helper('core/url')->getCurrentUrl();
            $referrerUrl = Mage::helper('core/http')->getHttpReferer();
            $request = sprintf(" HTTP 404  |  Requested Url => %s | Referrer Url => %s | Store name => %s", $requestedUrl, $referrerUrl, $this->getStoreName());
            if ($notificationType === self::NOTIFICATION_TYPE_LOG || $notificationType === self::NOTIFICATION_TYPE_COMBINE) {
                $fileName = $this->getLogfileName();
                Mage::log($request, Zend_Log::INFO, $fileName, false);
                $result = true;
            }
            if ($notificationType === self::NOTIFICATION_TYPE_DATABASE || $notificationType === self::NOTIFICATION_TYPE_COMBINE) {
                $log = Mage::getModel('sf_log404/log404');
                $data = array(
                    'store_id' => $this->getStoreId(),
                    'requested_url' => $requestedUrl,
                    'referrer_url' => $referrerUrl,
                    'description'=> 'HTTP 404',
                    'created_at' => Varien_Date::now()
                );
                $log->addData($data);
                $log->save();
                $result = true;
            }
        }
        return $result;
    }

}
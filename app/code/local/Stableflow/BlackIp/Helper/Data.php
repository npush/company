<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 3:27 PM
 */
class Stableflow_BlackIp_Helper_Data extends Mage_Core_Helper_Data
{

    /**
     * Check if IP blocked
     * @param $ip
     * @return bool
     */
    public function checkIfBlocked($ip){


        $arrIp = explode('.', $ip);
        $model = Mage::getModel('sf_blackip/blacklist')->getCollection();
        $item = $model->addFieldToFilter('black_ip', $ip)->getFirstItem();
        if($item->getData()){
            return true;
        }
        return false;
        if(file_exists($this->cacheFile)) {
            $arrBlocked = unserialize(file_get_contents($this->cacheFile));
        }
        if(!empty($arrBlocked)){
            foreach($arrBlocked as $v){

                $tmp_ip = explode('.', $v);
                $found = 0;
                foreach($tmp_ip as $key => $val){
                    if($val == '*'){
                        $found++;
                    }elseif($arrIp[$key] == $val){
                        $found++;
                    }
                }
                if($found == 4){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     */
    public function getVisitors()
    {
        $collection = Mage::getModel('log/visitor')->getCollection();
    }

    public function getCommentsLeftInfo()
    {

    }
}
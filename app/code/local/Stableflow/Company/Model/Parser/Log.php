<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:17 PM
 */
class Stableflow_Company_Model_Parser_Log extends Mage_Core_Model_Abstract
{

    protected $_taskId = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_log');
    }

    public function getLogCollection($company_id = null, $status = null)
    {

        $collection = $this->getCollection();
        if(!is_null($company_id)){
            $collection->addFieldToFilter('company_id', array('in' => $company_id));
        }
        if(!is_null($status)){
            $collection->addFieldToFilter('status_id', array('eq' => $status));
        }
        return $collection;
    }

    public function getErrorsLinesIds($taskId = null)
    {

    }

    public function st($status){
        if($status['status']) {
            $result['type'] = 'success';
            $result['message'] = Mage::helper('stableflow_pricelists')->__('Configuration saved. Prices successfully updated.');
            $result['message'] .= Mage::helper('stableflow_pricelists')->__(" Skipped Items: {$status['skipped']}, Saved Items: {$status['saved']}, Total: {$status['total']}");
        } else {
            $result['type'] = 'error';
            $result['message'] = "code required";
        }
    }
}
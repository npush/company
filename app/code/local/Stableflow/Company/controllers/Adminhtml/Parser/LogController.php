<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 1:48 PM
 */
class Stableflow_Company_Adminhtml_Parser_LogController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @see Mage_Core_Controller_Varien_Action::_construct()
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Parser_Log');
    }

    protected function _initLog()
    {
        $log = false;
        $logId  = (int) $this->getRequest()->getParam('log_id');
        if ($logId) {
            $log = Mage::getModel('company/parser_price_log');
            $log->load($logId);
            Mage::register('current_log', $log);
        }
        return $log;
    }

    /**
     * default action for company controller
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function indexAction()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Prise parser'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * View List of parser log
     */
    public function taskLogAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
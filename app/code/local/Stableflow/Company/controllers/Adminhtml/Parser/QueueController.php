<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 1:48 PM
 */
class Stableflow_Company_Adminhtml_Parser_QueueController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @see Mage_Core_Controller_Varien_Action::_construct()
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Parser');
    }

    protected function _initQueue()
    {
        $queueId  = (int) $this->getRequest()->getParam('queue_id');
        $queue = Mage::getModel('company/parser_queue');

        if ($queueId) {
            $queue->load($queueId);
        }
        Mage::register('current_queue', $queue);
        return $queue;
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

    public function queueAction()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Queue List'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editQueueAction()
    {

    }
}
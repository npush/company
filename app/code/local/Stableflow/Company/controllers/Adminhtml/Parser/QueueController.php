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
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author nick
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Parser');
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
}
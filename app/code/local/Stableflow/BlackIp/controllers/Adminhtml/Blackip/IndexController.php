<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 6:10 PM
 */
class Stableflow_BlackIp_Adminhtml_Blackip_IndexController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('sf_blackip/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {

        $this->_initAction();
        //$this->_addContent($this->getLayout()->createBlock('visitoripsecurity/adminhtml_visitoripsecurity_grid'));
        $this->renderLayout();
        //exit();

    }

    public function blockedAction() {

        $this->_initAction();
        //$this->_addContent($this->getLayout()->createBlock('visitoripsecurity/adminhtml_visitoripsecurity_blocked_grid'));
        $this->renderLayout();
        //exit();

    }

    public function oneipAction() {

        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('visitoripsecurity/adminhtml_visitoripsecurity_oneip_block'));
        $this->renderLayout();
        //exit();

    }
}
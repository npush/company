<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 4:31 PM
 */

class Stableflow_Log404_Adminhtml_Log404Controller extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('rulletka/log404')
            ->_title($this->__('Rulletka'))->_title($this->__('Log404'))
            ->_addBreadcrumb($this->__('Rulletka'), $this->__('Rulletka'))
            ->_addBreadcrumb($this->__('Log404'), $this->__('Log404'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sf_log404/log404');
    }

}
<?php

/**
 * ParserController.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 7/29/17
 *
 */
class Stableflow_Company_Adminhtml_Parser_ParserController extends Mage_Adminhtml_Controller_Action
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

    public function taskAction()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Task List'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function logAction()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Task List'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Open configuration popup window
     * Json Editor
     */
    public function openConfigurationPopupAction()
    {
//      $this->loadLayout('popup');
//      $this->renderLayout();
        $fieldId = (int) $this->getRequest()->getParam('id');
        $config = Mage::getModel('company/parser_config')->load($fieldId);
        $block = $this->getLayout()->createBlock('core/template')->setTemplate('company/parser/editor.phtml');
        $block->setData('settings', $config->getSettings());
        echo $block->toHtml();
    }

    /**
     * Update configuration save and update
     * @throws Exception
     */
    public function updateConfigurationAction(){
        $fieldId = (int) $this->getRequest()->getParam('id');
        $config = $this->getRequest()->getParam('settings');
        if ($fieldId) {
            $model = Mage::getModel('company/parser_config')->load($fieldId);
            $model->setConfig($config);
            $model->save();
        }
    }
}
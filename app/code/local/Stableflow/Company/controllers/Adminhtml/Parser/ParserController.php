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

    public function parserConfigurationAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('parser_configuration_grid');
            //->setProductsGrouped($this->getRequest()->getPost('products_grouped', null));
        $this->renderLayout();
    }


    /**
     * Open Setting editor window
     * Json Editor
     */
    public function editParserSettingAction()
    {
        $fieldId = (int) $this->getRequest()->getParam('id');
        $config = Mage::getModel('company/parser_config')->load($fieldId);
        $a = $config->getSettingsObject();
        $this->loadLayout();
        $this->getLayout()->getBlock('parser_settings_editor')->setData('settings', $config->getSettings());
        $this->renderLayout();
    }

    /**
     * Save Parser Setting
     * @throws Exception
     */
    public function saveParserSettingAction()
    {
        $fieldId = (int) $this->getRequest()->getParam('id');
        $config = json_decode($this->getRequest()->getParam('settings'), true);
        $fieldId = 3;
        if ($fieldId) {
            $model = Mage::getModel('company/parser_config')->load($fieldId);
            $model->setSettings($config);
            $model->save();
        }
    }

    public function addParserConfigurationAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewPriceTypeAction()
    {
        $companyId = (int) $this->getRequest()->getParam('id');
        Mage::register('company_id', $companyId);
        $this->loadLayout();
        $this->getLayout()->getBlock('price_type_grid');
        $this->renderLayout();

    }

    public function addPriceTypeAction()
    {
        $companyId = (int) $this->getRequest()->getParam('id');
        $this->loadLayout();
        $this->getLayout()->getBlock('price_type_form');
        Mage::register('company_id', $companyId);
        $this->renderLayout();
    }

    public function savePriceTypeAction()
    {

    }
}
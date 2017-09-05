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
     * @see Mage_Core_Controller_Varien_Action::_construct()
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Parser');
    }

    /**
     * init the Price Type
     *
     * @return Stableflow_Company_Model_Parser_Price_Type
     */
    protected function _initPriceType()
    {
        $typeId  = (int) $this->getRequest()->getParam('type_id');
        $type = Mage::getModel('company/parser_price_type');
        if ($typeId) {
            $type->load($typeId);
        }
        Mage::register('current_price_type', $type);
        return $type;
    }

    /**
     * init the Configuration
     *
     * @return Stableflow_Company_Model_Parser_Config
     */
    protected function _initConfiguration()
    {
        $configId  = (int) $this->getRequest()->getParam('config_id');
        $config = Mage::getModel('company/parser_config');
        if ($configId) {
            $config->load($configId);
        }
        Mage::register('current_configuration', $config);
        return $config;
    }

    /**
     * init the Settings
     *
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    protected function _initSettings()
    {

    }

    /**
     * Default action for parser controller
     */
    public function indexAction()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Prise parser'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * View List of all configurations
     */
    public function parserConfigurationAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new configuration action
     */
    public function newConfigurationAction()
    {
        $this->_forward('editConfiguration');
    }

    /**
     * Ajax
     * Edit action
     */
    public function editConfigurationAction()
    {
        $configId  = (int) $this->getRequest()->getParam('config_id');
        $config = $this->_initConfiguration();
        if ($configId && !$config->getId()) {
            $this->_getSession()->addError(Mage::helper('company')->__('This configuration no longer exists.'));
            $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
        Mage::dispatchEvent('stableflow_company_parser_editConfiguration_action', array('configuration' => $config));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save action
     */
    public function saveConfigurationAction()
    {
        $data = $this->getRequest()->getPost();
    }

    /**
     * Delete action
     */
    public function deleteConfigurationAction()
    {

    }

    /**
     * mass delete action
     */
    public function massDeleteConfigurationAction()
    {

    }

    /**
     * mass change status action
     */
    public function massStatusConfigurationAction()
    {

    }

    /**
     * new configuration action
     *
     * @return void
     */
    public function newParserSettingAction()
    {
        $this->_forward('editParserSettings');
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
     * Json Editor
     * Save Parser Setting
     * @throws Exception
     */
    public function saveParserSettingAction()
    {
        $configId = (int) $this->getRequest()->getParam('config_id');
        $settings = json_decode($this->getRequest()->getParam('settings'), true);
        $configId = 3;
        if ($configId) {
            try {
                $model = Mage::getModel('company/parser_config')->load($configId);
                $model->setSettings($settings);
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('company')->__('Configuration was successfully saved')
                );
                $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
            }catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
    }

    /**
     * View List of all Price Types
     */
    public function priceTypeAction()
    {
        $companyId = (int) $this->getRequest()->getParam('id');
        Mage::register('company_id', $companyId);
        $this->loadLayout();
        $this->getLayout()->getBlock('parser_pricetype_grid');
        //->setProductsGrouped($this->getRequest()->getPost('products_grouped', null));
        $this->renderLayout();
    }

    /**
     * new configuration action
     *
     * @return void
     */
    public function newPriceTypeAction()
    {
        $this->_forward('editPriceType');
    }

    public function editPriceTypeAction()
    {
        $companyId = (int) $this->getRequest()->getParam('id');
        $this->loadLayout();
        $this->getLayout()->getBlock('price_type_form');
        Mage::register('company_id', $companyId);
        $this->renderLayout();
    }

    public function savePriceTypeAction()
    {
        $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
    }

    public function deletePriceTypeAction()
    {

    }

    public function massDeletePriceTypeAction()
    {

    }

    public function massStatusPriceTypeAction()
    {

    }

}
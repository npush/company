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
        $companyId = $this->getRequest()->getParam('id');
        $this->_getSession()->setCompanyId($companyId);
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
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            return;
        }
        Mage::dispatchEvent('stableflow_company_parser_editConfiguration_action', array('configuration' => $config));
        Mage::register('current_config', $config);
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save action
     */
    public function saveConfigurationAction()
    {
        $model = $this->_initConfiguration();
        $data = $this->getRequest()->getPost();
        try{
            if ($model->getCreatedAt == NULL || $model->getUpdatedAt() == NULL) {
                $model->setCreatedAt(now())->setUpdateAt(now());
            } else {
                $model->setUpdatedAt(now());
            }
            $model->addData($data);
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('company')->__('Configuration was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }
    }

    /**
     * Delete action
     */
    public function deleteConfigurationAction()
    {
        if ($id = $this->getRequest()->getParam('config_id')) {
            $task = Mage::getModel('company/parser_config')->load($id);
            try {
                $task->delete();
                $this->_getSession()->addSuccess(Mage::helper('company')->__('The configuration has been deleted.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()))
        );
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
        $this->getLayout()->getBlock('parser_settings_editor')->setData('task_id', $fieldId);
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
        if ($configId) {
            try {
                $model = Mage::getModel('company/parser_config')->load($configId);
                $model->setSettings($settings);
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('company')->__('Configuration was successfully saved')
                );
                $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            }catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            }
        }
    }

    /**
     * View List of all Price Types
     */
    public function priceTypeAction()
    {
        $companyId = $this->getRequest()->getParam('id');
        $this->_getSession()->setCompanyId($companyId);
        $this->loadLayout();
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
        $typeId = (int) $this->getRequest()->getParam('type_id');
        $type = $this->_initPriceType();
        if ($typeId && !$type->getId()) {
            $this->_getSession()->addError(Mage::helper('company')->__('This Price Type no longer exists.'));
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function savePriceTypeAction()
    {
        $model = $this->_initPriceType();
        $data = $this->getRequest()->getPost();
        try{
            if ($model->getCreatedAt == NULL || $model->getUpdatedAt() == NULL) {
                $model->setCreatedAt(now())->setUpdateAt(now());
            } else {
                $model->setUpdatedAt(now());
            }
            $data['company_id'] = $this->_getSession()->getCompanyId();
            $model->addData($data);
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('company')->__('Price Type was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }
    }

    public function deletePriceTypeAction()
    {
        if ($id = $this->getRequest()->getParam('type_id')) {
            $task = Mage::getModel('company/parser_price_type')->load($id);
            try {
                $task->delete();
                $this->_getSession()->addSuccess(Mage::helper('company')->__('The Price Type has been deleted.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()))
        );
    }

    public function massDeletePriceTypeAction()
    {

    }

    public function massStatusPriceTypeAction()
    {

    }

    /**
     * Start parser process action.
     *
     * @return void
     */
    public function startAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data) {
            $this->loadLayout(false);

            /** @var $resultBlock Mage_ImportExport_Block_Adminhtml_Import_Frame_Result */
            $resultBlock = $this->getLayout()->getBlock('import.frame.result');
            /** @var $parserModel Stableflow_Company_Model_Parser */
            $parserModel = Mage::getModel('company/parser');

            try {
                $parserModel->importSource();
                $resultBlock->addAction('show', 'import_validation_container')
                    ->addAction('innerHTML', 'import_validation_container_header', $this->__('Status'));
            } catch (Exception $e) {
                $resultBlock->addError($e->getMessage());
                $this->renderLayout();
                return;
            }
            $resultBlock->addAction('hide', array('edit_form', 'upload_button', 'messages'))
                ->addSuccess($this->__('Import successfully done.'));
            $this->renderLayout();
        } else {
            $this->_redirect('*/*/index');
        }
    }

//    public function gridAction()
//    {
//        $this->loadLayout();
//        $this->getResponse()->setBody(
//        $this->getLayout()->createBlock('company/adminhtml_parser_task_grid')->toHtml());
//    }

}
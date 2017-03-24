<?php

class Stableflow_Pricelists_Adminhtml_PricelistsController extends Mage_Adminhtml_Controller_Action {


    public function indexAction() {
        $this->_title($this->__('Price Lists'));
        $this->loadLayout();
        $this->_setActiveMenu('stableflow_pricelists');
        $this->_addBreadcrumb(Mage::helper('stableflow_pricelists')->__('Price Lists'), Mage::helper('stableflow_pricelists')->__('Price Lists'));
        $this->renderLayout();
    }

    public function newAction() {
        $this->_title($this->__('Add new price list'));
        $this->loadLayout();
        $this->_setActiveMenu('stableflow_pricelists');
        $this->_addBreadcrumb(Mage::helper('stableflow_pricelists')->__('Add new price list'), Mage::helper('stableflow_pricelists')->__('Add new price list'));
        $this->renderLayout();
    }

    public function editAction() {
        $this->_title($this->__('Edit price list'));
        $this->loadLayout();
        $this->_setActiveMenu('stableflow_pricelists');
        $this->_addBreadcrumb(Mage::helper('stableflow_pricelists')->__('Edit price list'), Mage::helper('stableflow_pricelists')->__('Edit Price list'));
        $this->renderLayout();
    }

    public function deleteAction() {
        $tipId = $this->getRequest()->getParam('id', false);

        try {
            Mage::getModel('pricelists/pricelist')->setId($tipId)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('stableflow_pricelists')->__('Price list successfully deleted'));

            return $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Somethings went wrong'));
        }

        $this->_redirectReferer();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
                try {
                    /** @var Stableflow_Pricelists_Model_Pricelist $pricelist */
                    $pricelist = Mage::getModel('pricelists/pricelist');
                    $path = Mage::getBaseDir('media') . DS . $pricelist->getPathToFile();
                    $filename = $_FILES['file']['name'];
                    $filename = $pricelist::translateFileName($filename);
                    $uploader = new Varien_File_Uploader('file');
                    $uploader->setAllowedExtensions(array('XLS','xls'));
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $uploader->save($path, $filename);

                    $pricelist->setFilename($filename);
                    $pricelist->setDate('NOW');
                    $pricelist->setStatus(Stableflow_Pricelists_Model_Resource_Pricelist::STATUS_NOT_APPROVED);
                    $pricelist->setIdCompany($this->getRequest()->getParam('id_company'));

                    if($pricelist->save()) {
                        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('stableflow_pricelists')->__('Price list successfully upload'));
                        $this->_redirect('*/*/');
                    }

                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError(Mage::helper('stableflow_pricelists')->__($e->getMessage()));
                    $this->_redirect('*/*/new');
                }
            }
        }
    }

    public function saveConfigAction() {
        try {
            $request = $this->getRequest();
            $id = $request->getParam('id');
            /** @var $priceList Stableflow_Pricelists_Model_Pricelist */
            $priceList = Mage::getModel('pricelists/pricelist')->load($id);
            Mage::register('current_pricelist', $priceList);

            $priceList->saveModel([
                'row' => $request->getParam('row'),
                'config' => $request->getParam('config')
            ]);

            Mage::getSingleton('core/session')->addSuccess(Mage::helper('stableflow_pricelists')->__('Configuration successfully save'));
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError(Mage::helper('stableflow_pricelists')->__($e->getMessage()));
        }

        return $this->_redirect('*/*/');
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('stableflow_pricelists/adminhtml_pricelists_grid')->toHtml());
    }
}

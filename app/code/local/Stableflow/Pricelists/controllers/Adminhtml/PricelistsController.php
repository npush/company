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
                    $path = Mage::getBaseDir('media') . DS . 'pricelists' . DS;
                    $filename = $_FILES['file']['name'];

                    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
                    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', '_');
                    $filename = str_replace($rus, $lat, $filename);

                    $uploader = new Varien_File_Uploader('file');
                    $uploader->setAllowedExtensions(array('XLS','xls'));
                    $uploader->setAllowCreateFolders(true);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $uploader->save($path, $filename);

                    $pricelist = Mage::getModel('pricelists/pricelist');
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

        $request = $this->getRequest();
        $id = $request->getParam('id');
        $row = $request->getParam('row');

        /** @var $priceList Stableflow_Pricelists_Model_Pricelist */
        $priceList = Mage::getModel('pricelists/pricelist')->load($id);
        Mage::register('current_pricelist', $priceList);

        $config = $request->getParam('config');
        $arrToSerialize = array();
        foreach ($config['value'] as $values) {
            $column = $values['column'];
            $letter = $values['letter'];
            $arrToSerialize['mapping'][$column] = $letter;
        }

        $arrToSerialize = array_merge($arrToSerialize, ['row' => $row]);
        $priceList->setConfig($arrToSerialize);
        $priceList->setDate('NOW');
        if($request->getParam('isApproved')) {
            $priceList->setStatus(Stableflow_Pricelists_Model_Resource_Pricelist::STATUS_APPROVED);
        }

        if($priceList->save()) {
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('stableflow_pricelists')->__('Configuration successfully save'));
        }

        return $this->_redirect('*/*/');
    }

    public function gridAction() {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('stableflow_pricelists/adminhtml_pricelists_grid')->toHtml());
    }
}

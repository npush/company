<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 12:49 PM
 */

class Stableflow_ProductTooltips_Adminhtml_TooltipsController extends Mage_Adminhtml_Controller_Action
{

    protected function _init()
    {
        $tooltip = Mage::getModel('product_tooltips/tooltip');
        $tooltipId  = (int) $this->getRequest()->getParam('id');
        if ($tooltipId) {
            $tooltip->load($tooltipId);
            Mage::register('current_tooltip', $tooltip);
        }
        return $tooltip;
    }

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
        $this->setUsedModuleName('Stableflow_ProductTooltips');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();

    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_init();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $post_data = $this->getRequest()->getPost();
        try {
            if ($post_data) {
                $tooltip = $this->_init();
                if ((bool)$post_data['value']['delete'] == 1) {
                    $post_data['value'] = '';
                } else {
                    unset($post_data['value']);
                    if (isset($_FILES)) {
                        if ($_FILES['value']['name']) {
//                            if ($this->getRequest()->getParam("id")) {
//                                if ($tooltip->getData('image')) {
//                                    $io = new Varien_Io_File();
//                                    $io->rm(Mage::getBaseDir('media') . DS . 'tooltips' . $tooltip->getData('image'));
//                                }
//                            }
                            $path = Mage::getBaseDir('media') . DS . 'tooltips' . DS;
                            $uploader = new Varien_File_Uploader('value');
                            $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                            $uploader->setAllowRenameFiles(true);
                            $uploader->setFilesDispersion(true);
                            $filename = $uploader->getNewFileName($_FILES['value']['name']);
                            $uploader->save($path, $filename);
                            $filename = $uploader->getUploadedFileName();
                            $post_data['value'] = $filename;
                        }
                    }
                }

                $tooltip->addData($post_data)->save();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Tooltip was successfully saved"));
                Mage::getSingleton("adminhtml/session")->setTooltipData(false);
                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $tooltip->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            }
        } catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
            Mage::getSingleton("adminhtml/session")->setTooltipData($this->getRequest()->getPost());
            $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            return;
        }
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam("id") > 0) {
            try {
                $model = $this->_init();
                $model->delete();
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
                $this->_redirect("*/*/");
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
            }
        }
        $this->_redirect("*/*/");
    }

//    public function ajaxAction()
//    {
//        $result = new Varien_Object();
//
//        // Output the result as JSON encoded
//        $this->getResponse()->setHeader('Content-type', 'application/json');
//        $this->getResponse()->setBody($result->toJson());
//    }

    public function updateAction()
    {
        // Load the layout
        $this->loadLayout(array('default', 'adminhtml_example_index'));

        // Get just part of the page
        $block = $this->getLayout()->getBlock('some_block_name');

        // Output the result after all processing is finished
        // or else you will get an "Headers already sent" error.
        $this->getResponse()->setBody($block->toHtml());
    }

    /**
     * grid action
     *
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}

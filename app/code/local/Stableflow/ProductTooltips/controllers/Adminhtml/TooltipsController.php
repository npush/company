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
        $tooltip = false;
        $tooltipId  = (int) $this->getRequest()->getParam('id');
        if ($tooltipId) {
            $tooltip = Mage::getModel('product_tooltips/tooltip');
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

    }

    public function ajaxAction()
    {
        $result = new Varien_Object();

        // Output the result as JSON encoded
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($result->toJson());
    }

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

    protected function _uploadImage()
    {
        try {
            if ((bool) $post_data['image']['delete'] == 1) {
                $post_data['image'] = '';
            } else {
                unset($post_data['image']);
                if (isset($_FILES)) {
                    if ($_FILES['image']['name']) {
                        if ($this->getRequest()->getParam("id")) {
                            $model = Mage::getModel("service/service")->load($this->getRequest()->getParam("id"));
                            if ($model->getData('image')) {
                                $io = new Varien_Io_File();
                                $io->rm(Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('image'))));
                            }
                        }
                        $path = Mage::getBaseDir('media') . DS . 'service' . DS . 'service' . DS;
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $destFile = $path . $_FILES['image']['name'];
                        $filename = $uploader->getNewFileName($destFile);
                        $uploader->save($path, $filename);

                        $post_data['image'] = 'service/service/' . $filename;
                    }
                }
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
    }

}

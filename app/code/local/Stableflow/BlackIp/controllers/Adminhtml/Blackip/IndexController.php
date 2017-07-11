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

    public function addnewAction() {

        $this->loadLayout();
        /*$this->_addContent($this->getLayout()->createBlock('form/adminhtml_form_edit'))
            ->_addLeft($this->getLayout()->createBlock('form/adminhtml_form_edit_tabs'));*/

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

    public function saveAction()
    {
        $storeId = $this->getRequest()->getParam('store');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $data = $this->getRequest()->getPost();
        if ($data) {
            unset($data['form_key']);
            $data['creation_time'] = Varien_Date::now();
            $model = Mage::getModel('sf_blackip/blacklist');
            $model->addData($data);
            try{
                $model->save();
                $this->_getSession()->addSuccess(
                    Mage::helper('sf_blackip')->__('IP was saved')
                );
            }
            catch(Mage_Core_Exception $e){

            }
        }
        $this->_redirect('*/*/', array('store'=>$storeId));
    }

    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('sf_blackip/blacklist');

                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $companyIds = $this->getRequest()->getParam('sf_blackip');
        if (!is_array($companyIds)) {
            $this->_getSession()->addError($this->__('Please select IP.'));
        } else {
            try {
                foreach ($companyIds as $companyId) {
                    $company = Mage::getSingleton('sf_blackip/blacklist')->load($companyId);
/*                    Mage::dispatchEvent(
                        'company_controller_company_delete',
                        array('company' => $company)
                    );*/
                    $company->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('company')->__('Total of %d record(s) have been deleted.', count($companyIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
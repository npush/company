<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 4:46 PM
 */

class Stableflow_Company_Adminhtml_Company_CompanyController extends Mage_Adminhtml_Controller_Action{

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
        $this->setUsedModuleName('Stableflow_Company');
    }

    /**
     * init the company
     *
     * @access protected
     * @return Stableflow_Company_Model_Company
     * @author nick
     */
    protected function _initCompany()
    {
        $this->_title($this->__('Company'))
            ->_title($this->__('Manage Companies'));

        $companyId  = (int) $this->getRequest()->getParam('id');
        $company    = Mage::getModel('company/company')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($companyId) {
            $company->load($companyId);
        }
        Mage::register('current_company', $company);
        return $company;
    }

    protected function _initProduct(){
        $productId = (int)$this->getRequest()->getParam('id');
        $product = Mage::getModel('company/product')->load($productId);
        return $product;
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
            ->_title($this->__('Manage Companies'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new company action
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit company action
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function editAction()
    {
        $companyId  = (int) $this->getRequest()->getParam('id');
        $company    = $this->_initCompany();
        if ($companyId && !$company->getId()) {
            $this->_getSession()->addError(
                Mage::helper('company')->__('This company no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getCompanyData(true)) {
            $company->setData($data);
        }
        $this->_title($company->getCompanyTitle());
        Mage::dispatchEvent(
            'stableflow_company_company_edit_action',
            array('company' => $company)
        );
        $this->loadLayout();
        if ($company->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('company')->__('Default Values'))
                    ->setWebsiteIds($company->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save company action
     *
     * @access public
     * @return void
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $companyId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $company     = $this->_initCompany();
            $companyData = $this->getRequest()->getPost('company', array());
            $company->addData($companyData);
            $company->setAttributeSetId($company->getDefaultAttributeSetId());
            if (isset($data['tags'])) {
                $tags = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['tags']);
                $company->setTagsData($tags);
            }
            $categories = $this->getRequest()->getPost('category_ids', -1);
            if ($categories != -1) {
                $categories = explode(',', $categories);
                $categories = array_unique($categories);
                $company->setCategoriesData($categories);
            }
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $company->setData($attributeCode, false);
                }
            }

            // Unset template data
            if (isset($data['address']['_template_'])) {
                unset($data['address']['_template_']);
            }
            $modifiedAddresses = array();
            if (!empty($data['address'])) {
                /** @var $addressForm Stableflow_Company_Model_Form */
                $addressForm = Mage::getModel('customer/form');
                $addressForm->setFormCode('adminhtml_company_address')->ignoreInvisible(false);

                foreach (array_keys($data['address']) as $index) {
                    $address = $company->getAddressItemById($index);
                    if (!$address) {
                        $address = Mage::getModel('company/address');
                    }

                    $requestScope = sprintf('address/%s', $index);
                    $formData = $addressForm->setEntity($address)
                        ->extractData($this->getRequest(), $requestScope);

                    // Set default billing and shipping flags to address
                    $isDefaultBilling = isset($data['account']['default_billing'])
                        && $data['account']['default_billing'] == $index;
                    $address->setIsDefaultBilling($isDefaultBilling);
                    $isDefaultShipping = isset($data['account']['default_shipping'])
                        && $data['account']['default_shipping'] == $index;
                    $address->setIsDefaultShipping($isDefaultShipping);

                    $errors = $addressForm->validateData($formData);
                    if ($errors !== true) {
                        foreach ($errors as $error) {
                            $this->_getSession()->addError($error);
                        }
                        $this->_getSession()->setCustomerData($data);
                        $this->getResponse()->setRedirect($this->getUrl('*/customer/edit', array(
                                'id' => $company->getId())
                        ));
                        return;
                    }

                    $addressForm->compactData($formData);

                    // Set post_index for detect default billing and shipping addresses
                    $address->setPostIndex($index);

                    if ($address->getId()) {
                        $modifiedAddresses[] = $address->getId();
                    } else {
                        $company->addAddress($address);
                    }
                }
            }
            // Default billing and shipping
            if (isset($data['account']['default_billing'])) {
                $company->setData('default_billing', $data['account']['default_billing']);
            }
            if (isset($data['account']['default_shipping'])) {
                $company->setData('default_shipping', $data['account']['default_shipping']);
            }
            if (isset($data['account']['confirmation'])) {
                $company->setData('confirmation', $data['account']['confirmation']);
            }

            // Mark not modified customer addresses for delete
            foreach ($company->getAddressesCollection() as $customerAddress) {
                if ($customerAddress->getId() && !in_array($customerAddress->getId(), $modifiedAddresses)) {
                    $customerAddress->setData('_deleted', true);
                }
            }
            try {
                $company->save();
                $companyId = $company->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('company')->__('Company was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setCompanyData($companyData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('company')->__('Error saving company')
                )
                    ->setCompanyData($companyData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $companyId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete company
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $company = Mage::getModel('company/company')->load($id);
            try {
                $company->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('company')->__('The company has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete companys
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function massDeleteAction()
    {
        $companyIds = $this->getRequest()->getParam('company');
        if (!is_array($companyIds)) {
            $this->_getSession()->addError($this->__('Please select company.'));
        } else {
            try {
                foreach ($companyIds as $companyId) {
                    $company = Mage::getSingleton('company/company')->load($companyId);
                    Mage::dispatchEvent(
                        'company_controller_company_delete',
                        array('company' => $company)
                    );
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

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function massStatusAction()
    {
        $companyIds = $this->getRequest()->getParam('company');
        if (!is_array($companyIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('company')->__('Please select companies.')
            );
        } else {
            try {
                foreach ($companyIds as $companyId) {
                    $company = Mage::getSingleton('company/company')->load($companyId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d companies were successfully updated.', count($companyIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('company')->__('There was an error updating companys.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Sam
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('company/company');
    }

    /**
     * Export companys in CSV format
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function exportCsvAction()
    {
        $fileName   = 'companys.csv';
        $content    = $this->getLayout()->createBlock('company/adminhtml_company_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export companys in Excel format
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function exportExcelAction()
    {
        $fileName   = 'company.xls';
        $content    = $this->getLayout()->createBlock('company/adminhtml_company_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export companys in XML format
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function exportXmlAction()
    {
        $fileName   = 'company.xml';
        $content    = $this->getLayout()->createBlock('company/adminhtml_company_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Sam
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'company/adminhtml_company_helper_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    public function generateSitemapAction()
    {
        Mage::getModel('company/generateSitemap')->generateXml();
    }

    public function editProductAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->removeOutputBlock('header');
        $this->renderLayout();
    }
    public function saveProductAction()
    {
        $storeId = $this->getRequest()->getParam('store');
        $productId = $this->getRequest()->getParam('id');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $model = Mage::getModel('company/product')->load($productId);
            $model->addData($data);
            try{
                $model->save();
                $this->_getSession()->addSuccess(
                    Mage::helper('company')->__('Product was saved')
                );
            }
            catch(Mage_Core_Exception $e){

            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }
}
<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/20/16
 * Time: 6:40 PM
 */
class Stableflow_Company_CompanyController extends Mage_Core_Controller_Front_Action {

    protected function _initCompany(){
        $companyId = $this->getRequest()->getParam('id', 0);
        $company = Mage::getModel('company/company')
            //->setStoreId(Mage::app()->getStore()->getId())
            ->load($companyId);
        if (!$company->getId()) {
            return false;
        } /*elseif (!$company->getStatus()) {
            return false;
        }*/
        return $company;
    }

    public function viewAction(){
        $company = $this->_initCompany();
        if (!$company) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_company', $company);
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle('Company view');
        }
        if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbBlock->addCrumb(
                'home',
                array(
                    'label' => Mage::helper('company')->__('Home'),
                    'link' => Mage::getUrl(),
                )
            )->addCrumb(
                'company_home',
                array(
                    'label' => Mage::helper('company')->__('Companies'),
                    'link' => Mage::getUrl('company'),
                )
            )->addCrumb(
                'company',
                array(
                    'label' => $company->getName(),
                    'link' => '',
                )
            );
        }
        $this->renderLayout();
    }

    public function productListAction(){
        $this->loadLayout();
        //$this->getLayout()->getBlock('root');
        //$this->renderLayout();
        $myBlock = $this->getLayout()->createBlock('ajax/product');
        $myBlock->setTemplate('company/product/list.phtml');
        $myHtml =  $myBlock->toHtml(); //also consider $myBlock->renderView();
        $this->getResponse()
            ->setHeader('Content-Type', 'text/html')
            ->setBody($myHtml);
        return;
    }

    protected function _getProductListCollection(){

    }
}
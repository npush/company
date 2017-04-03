<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:18 PM
 */
class Stableflow_Company_IndexController extends Mage_Core_Controller_Front_Action {

    protected function _initCompanyList(){
        $post = Mage::app()->getRequest()->getParam('company_type');
        print_r($post);die();
        $type = Mage::getModel('company/company_attribute_source/');
        /** @var  $company Stableflow_Company_Model_Company */
        $company = Mage::getModel('company/company')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('type','')
            ->setOrder('name','asc');
        //->addAttributeToFilter('status', 1);
        $this->setCompany($company);
    }

    public function indexAction(){
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Companies'));
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
                    'link' =>  Mage::helper('core/url')->getCurrentUrl(),
                )
            );
        }
        $this->renderLayout();
    }

    public function listAction(){
        $post = Mage::app()->getRequest()->getParam('company_type');
        $type = Mage::getModel('company/company_attribute_source_type')->getOptionArray();
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Companies'));
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
                    'link' =>  Mage::helper('core/url')->getCurrentUrl(),
                )
            )->addCrumb(
                'company_',
                array(
                    'label' => $type[$post],
                    'link' =>  Mage::helper('core/url')->getCurrentUrl(),
                )
            )
            ;
        }
        $this->renderLayout();
    }

}
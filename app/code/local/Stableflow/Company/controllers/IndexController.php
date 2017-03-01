<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:18 PM
 */
class Stableflow_Company_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexoldAction(){
        $companies = Mage::getModel('company/company')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('description');
        $companies->load();
        //var_dump($companies);
        foreach($companies as $company){
            echo '<div>';
            echo '<h3>'.$company->getName().'</h3>';
            echo '<h3>'.$company->getDescription().'</h3>';
            echo '<h3>'.$company->getActivity().'</h3>';
            echo '</div>';
        }
    }

    public function indexAction(){
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle('Companies');
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


    public function addAction(){
        /** @var  $companies Stableflow_Company_Model_Company */
        $companies = Mage::getModel('company/company');
        $companies->setData('name','Demo Company 3');
        $companies->setData('description','Description ha ha ha 4');
        $companies->setData('activity','4');
        $companies->setData('type','2');
        $companies->setData('image','logo.jpeg');
        $companies->setData('url','http://magento.dev/company3');
        $companies->save();
    }
}
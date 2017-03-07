<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/7/17
 * Time: 3:43 PM
 */
class Stableflow_Company_Block_Company_Review extends Mage_Review_Block_Form{

    public function getAction()
    {
        $companyId = Mage::app()->getRequest()->getParam('id', false);
        return Mage::getUrl('company/company/review', array('id' => $companyId, '_secure' => $this->_isSecure()));
    }

    public function getCompanyInfo()
    {
        $company = Mage::getModel('company/company');
        return $company->load($this->getRequest()->getParam('id'));
    }

    public function getRatings()
    {
        $ratingCollection = Mage::getModel('rating/rating')
            ->getResourceCollection()
            ->addEntityFilter('company')
            ->setPositionOrder()
            ->addRatingPerStoreName(Mage::app()->getStore()->getId())
            ->setStoreFilter(Mage::app()->getStore()->getId())
            ->load()
            ->addOptionToItems();
        return $ratingCollection;
    }
}
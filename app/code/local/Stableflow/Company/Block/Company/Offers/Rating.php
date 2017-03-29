<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/29/17
 * Time: 2:14 PM
 */
class Stableflow_Company_Block_Company_Offers_Rating extends Mage_Core_Block_Template
{

    public function getCompanyRating($companyId){
        $entityId = Mage::app()->getRequest()->getParam('id');
        if (intval($entityId) <= 0) {
            return '';
        }

        $reviewsCount = Mage::getModel('review/review')
            ->getTotalReviews($entityId, true);
        if ($reviewsCount == 0) {
            #return Mage::helper('rating')->__('Be the first to review this product');
            $this->setTemplate('company/company/rating/empty.phtml');
            return parent::_toHtml();
        }

        $ratingCollection = Mage::getModel('rating/rating')
            ->getResourceCollection()
            ->addEntityFilter('company') # TOFIX
            ->setPositionOrder()
            ->setStoreFilter(Mage::app()->getStore()->getId())
            ->addRatingPerStoreName(Mage::app()->getStore()->getId())
            ->load();

        if ($entityId) {
            $ratingCollection->addEntitySummaryToItem($entityId, Mage::app()->getStore()->getId());
        }

        $this->assign('collection', $ratingCollection);
    }
}
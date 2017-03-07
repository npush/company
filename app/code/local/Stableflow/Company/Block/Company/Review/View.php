<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/7/17
 * Time: 4:32 PM
 */
class Stableflow_Company_Block_Company_Review_View extends Stableflow_Company_Block_Company{

    protected $_reviewsCollection;

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->getCompany()->setShortDescription(null);

        return parent::_toHtml();
    }

    /**
     * Replace review summary html with more detailed review summary
     * Reviews collection count will be jerked here
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function getReviewsSummaryHtml(Stableflow_Company_Model_Company $company, $templateType = false, $displayIfNoReviews = false)
    {
        return
            $this->getLayout()->createBlock('rating/entity_detailed')
                ->setEntityId($this->getCompany()->getId())
                ->toHtml()
            .
            $this->getLayout()->getBlock('product_review_list.count')
                ->assign('count', $this->getReviewsCollection()->getSize())
                ->toHtml()
            ;
    }

    public function getReviewsCollection()
    {
        if (null === $this->_reviewsCollection) {
            $this->_reviewsCollection = Mage::getModel('review/review')->getCollection()
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->addEntityFilter('company', $this->getCompany()->getId())
                ->setDateOrder();
        }
        return $this->_reviewsCollection;
    }

    /**
     * Force product view page behave like without options
     *
     * @return false
     */
    public function hasOptions()
    {
        return false;
    }
}
<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/7/17
 * Time: 3:53 PM
 */

class Stableflow_Company_Block_Company_Review_List extends Stableflow_Company_Block_Company_Review_View{

    protected $_forceHasOptions = false;

    public function getCompanyId()
    {
        return Mage::registry('current_company')->getId();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($toolbar = $this->getLayout()->getBlock('product_review_list.toolbar')) {
            $toolbar->setCollection($this->getReviewsCollection());
            $this->setChild('toolbar', $toolbar);
        }

        return $this;
    }

    protected function _beforeToHtml()
    {
        $this->getReviewsCollection()
            ->load()
            ->addRateVotes();
        return parent::_beforeToHtml();
    }

    public function getReviewUrl($id)
    {
        return Mage::getUrl('company/company/review-view', array('id' => $id));
    }
}
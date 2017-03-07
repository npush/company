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
        // -----
        $isAjax = Mage::app()->getRequest()->getParam('is_ajax');
        if($isAjax){
            $this->loadLayout();
            $myBlock = $this->getLayout()->getBlock('company_product_list');
            $myHtml =  $myBlock->toHtml(); //also consider $myBlock->renderView();
            $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($myHtml);
            return;
        }
        // -----
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
        $this->getLayout()->getBlock('root');
        $this->renderLayout();
/*        $myBlock = $this->getLayout()->createBlock('ajax/product');
        $myBlock->setTemplate('company/product/list.phtml');
        $myHtml =  $myBlock->toHtml(); //also consider $myBlock->renderView();
        $this->getResponse()
            ->setHeader('Content-Type', 'text/html')
            ->setBody($myHtml);
        return;*/
    }

    protected function _getProductListCollection(){

    }

    public function reviewAction(){
        if (!$this->_validateFormKey()) {
            // returns to the product item page
            $this->_redirectReferer();
            return;
        }

        if ($data = Mage::getSingleton('review/session')->getFormData(true)) {
            $rating = array();
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data   = $this->getRequest()->getPost();
            $rating = $this->getRequest()->getParam('ratings', array());
        }

        if (($company = $this->_initCompany()) && !empty($data)) {
            $session = Mage::getSingleton('core/session');
            /* @var $session Mage_Core_Model_Session */
            $review = Mage::getModel('review/review')->setData($this->_cropReviewData($data));
            /* @var $review Mage_Review_Model_Review */

            $validate = $review->validate();
            if ($validate === true) {
                try {
                    $review->setEntityId($review->getEntityIdByCode(Stableflow_Company_Model_Review::ENTITY_COMPANY_CODE))
                        ->setEntityPkValue($company->getId())
                        ->setStatusId(Mage_Review_Model_Review::STATUS_PENDING)
                        ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->setStores(array(Mage::app()->getStore()->getId()))
                        ->save();

                    foreach ($rating as $ratingId => $optionId) {
                        Mage::getModel('rating/rating')
                            ->setRatingId($ratingId)
                            ->setReviewId($review->getId())
                            ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                            ->addOptionVote($optionId, $company->getId());
                    }

                    $review->aggregate();
                    $session->addSuccess($this->__('Your review has been accepted for moderation.'));
                }
                catch (Exception $e) {
                    $session->setFormData($data);
                    $session->addError($this->__('Unable to post the review.'));
                }
            }
            else {
                $session->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $session->addError($errorMessage);
                    }
                }
                else {
                    $session->addError($this->__('Unable to post the review.'));
                }
            }
        }

        if ($redirectUrl = Mage::getSingleton('review/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }
        $this->_redirectReferer();
    }

    /**
     * Crops POST values
     * @param array $reviewData
     * @return array
     */
    protected function _cropReviewData(array $reviewData)
    {
        $croppedValues = array();
        $allowedKeys = array_fill_keys(array('detail', 'title', 'nickname'), true);

        foreach ($reviewData as $key => $value) {
            if (isset($allowedKeys[$key])) {
                $croppedValues[$key] = $value;
            }
        }

        return $croppedValues;
    }
}
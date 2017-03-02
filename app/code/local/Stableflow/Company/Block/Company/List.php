<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:35 PM
 */

class Stableflow_Company_Block_Company_List extends Mage_Core_Block_Template{

    protected $company = null;

    public function _construct(){
        parent::_construct();
        /** @var  $company Stableflow_Company_Model_Company */
        $company = Mage::getModel('company/company')->getCollection()
            ->addAttributeToSelect('*')
            ->setOrder('name','asc');
        //->addAttributeToFilter('status', 1);
        $this->setCompany($company);
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        // pager
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'company.list.html.pager'
        )->setCollection($this->getCompany());
        $this->setChild('pager', $pager);
        $this->getCompany()->load();
        return $this;
    }

    public function getImageUrl($company){
        return Mage::getBaseUrl('media') . 'company' . $company->getImage();
    }
}
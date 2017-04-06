<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/4/17
 * Time: 4:15 PM
 */
class Stableflow_Company_Block_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    public function getPagerHtml()
    {
        $pagerBlock = $this->getLayout()->createBlock('page/html_pager');
        if ($pagerBlock instanceof Varien_Object)
        {
            /* here you can customize your toolbar like h*/
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());
            $pagerBlock->setUseContainer(false)
                ->setShowPerPage(false)
                ->setShowAmounts(false)
                ->setLimitVarName($this->getLimitVarName())
                ->setPageVarName($this->getPageVarName())
                ->setLimit($this->getLimit())
                ->setCollection($this->getCollection());
            return $pagerBlock->toHtml();
        }
        return '';
    }
}
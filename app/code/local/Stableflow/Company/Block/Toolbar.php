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
        $pagerBlock = $this->getLayout()->createBlock('company/pager');
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

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = false;
        $urlParams['_query']    = $params;
        return $this->getUrl('company/company/productList', $urlParams);
    }
}
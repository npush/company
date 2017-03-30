<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/30/17
 * Time: 1:40 PM
 */

class Stableflow_Company_Block_Catalog_Layer_View extends Mage_Catalog_Block_Layer_View
{
    const SALE_FILTER_POSITION = 2;

    /**
     * State block name
     *
     * @var string
     */
    protected $_saleBlockName;

    /**
     * Initialize blocks names
     */
    protected function _initBlocks()
    {
        parent::_initBlocks();

        $this->_saleBlockName = 'stableflow_company/catalog_layer_filter_sale';
    }

    /**
     * Prepare child blocks
     *
     * @return Mage_Catalog_Block_Layer_View
     */
    protected function _prepareLayout()
    {
        $saleBlock = $this->getLayout()->createBlock($this->_saleBlockName)
            ->setLayer($this->getLayer())
            ->init();

        $this->setChild('company_filter', $saleBlock);

        return parent::_prepareLayout();
    }

    /**
     * Get all layer filters
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = parent::getFilters();

        if (($saleFilter = $this->_getSaleFilter())) {
            // Insert sale filter to the self::SALE_FILTER_POSITION position
            $filters = array_merge(
                array_slice(
                    $filters,
                    0,
                    self::SALE_FILTER_POSITION - 1
                ),
                array($saleFilter),
                array_slice(
                    $filters,
                    self::SALE_FILTER_POSITION - 1,
                    count($filters) - 1
                )
            );
        }

        return $filters;
    }

    /**
     * Get sale filter block
     *
     * @return Mage_Catalog_Block_Layer_Filter_Sale
     */
    protected function _getSaleFilter()
    {
        return $this->getChild('sale_filter');
    }

}
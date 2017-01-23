<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/23/17
 * Time: 6:02 PM
 */

class Stableflow_Rulletka_Helper_Breadcrumbs extends Mage_Core_Helper_Abstract {
    /**
     * Current product
     * @var Mage_Catalog_Model_Product
     */
    protected $_currentProduct = null;
    /**
     * Actual category for current product
     * * @var Mage_Catalog_Model_Category
     */
    protected $_currentCategory = null;
    /**
     * Category path for current product
     * @var array
     */
    protected $_categoryPath = null;
    /**
     * Returns true if there is a product page
     *
     * @return bool
     */
    public function isItProductPage()
    {
        if (is_null($this->_currentProduct)) {
            $this->_currentProduct = Mage::registry('current_product');
        }
        if (is_object($this->_currentProduct))
            return true;
        return false;
    }
    /**
     * Returns true if there is a current_category
     *
     * @return bool
     */
    public function hasCurrentCategory()
    {
        if (is_null($this->_currentCategory)) {
            $this->_currentCategory = Mage::registry('current_category');
        }
        if (is_object($this->_currentCategory))
            return true;
        return false;
    }
    /**
     * Returns current product categories data as structured array
     *
     * @return array
     */
    public function getCategoryPath()
    {
        if (is_null($this->_categoryPath)) {
            if ($this->isItProductPage()) {
                return $this->_getBreadcrumbPath();
            }
        }
        return array();
    }
    /**
     * Generates current product categories path
     *
     * @return array
     */
    protected function _getProductBreadcrumbCategory()
    {
        if (!is_null($this->_currentProduct)) {
            $productCatsList = $this->_currentProduct->getCategoryCollection()->exportToArray();
            if (count($productCatsList) > 0) {
                $curr_path = '';
                foreach ($productCatsList as $category_id => $category_data) {
                    if ( ($curr_path == '') || (($curr_path . '/' . $category_id) == $category_data['path']) ) {
                        $curr_path = $category_data['path'];
                        $actualCat = $category_data;
                    }
                }
                $this->_currentCategory = Mage::getModel('catalog/category')->load($actualCat['entity_id']);
            }
        }
        return $this->_currentCategory;
    }
    /**
     * Return current category path or get it from current category
     * and creating array of categories|product paths for breadcrumbs
     *
     * @return string
     */
    protected function _getBreadcrumbPath()
    {
        $path = array();
        if ($category = $this->_getProductBreadcrumbCategory()) {
            $pathInStore = $category->getPathInStore();
            $pathIds = array_reverse(explode(',', $pathInStore));
            $categories = $category->getParentCategories();
            // add category path breadcrumb
            foreach ($pathIds as $categoryId) {
                if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                    $path['category'.$categoryId] = array(
                        'label' => $categories[$categoryId]->getName(),
                        'link' => $categories[$categoryId]->getUrl()
                    );
                }
            }
        }
        if ($this->_currentProduct) {
            $path['product'] = array('label'=>$this->_currentProduct->getName());
        }
        $this->_categoryPath = $path;
        return $this->_categoryPath;
    }
}
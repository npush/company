<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/12/17
 * Time: 12:12 PM
 */

class  Stableflow_Rulletka_Block_Subcategory extends Mage_Core_Block_Template {

    const BASE_LEVEL = 1;
    const SECOND_LEVEL = 2;
    const LAST_LEVEL = 3;

    /**
     * categoryTree data tree
     *
     * @var Varien_Data_Tree_Node
     */
    protected $_categoryTree;

    protected $_templateFile;

    /**
     * Current child categories collection
     *
     * @var Mage_Catalog_Model_Resource_Category_Collection
     */
    protected $_currentChildCategories;

    public function _construct(){
        $this->_categoryTree = new Varien_Data_Tree_Node(array(), 'root', new Varien_Data_Tree());
        /*
        * setting cache to save the topmenu block
        */
        $this->setCacheTags(array('catalog_subcategory_tree'));
        $this->setCacheLifetime(false);

        $this->getCurrentChildCategories();

        $this->_addCategoriesToMenu(
            $this->getCurrentChildCategories(), $this->_categoryTree);

    }

    /**
     * Retrieve child categories of current category
     *
     * @return Mage_Catalog_Model_Resource_Category_Collection
     */
    public function getCurrentChildCategories()
    {
        if (null === $this->_currentChildCategories) {
            $layer = Mage::getSingleton('catalog/layer');
            $category = $layer->getCurrentCategory();
            $this->_currentChildCategories = $category->getChildrenCategories();
            $productCollection = Mage::getResourceModel('catalog/product_collection');
            $layer->prepareProductCollection($productCollection);
            $productCollection->addCountToCategories($this->_currentChildCategories);
        }
        return $this->_currentChildCategories;
    }

    protected function _renderCategoryTree($category){

    }

    /**
     * Renders block html
     * @return string
     * @throws Exception
     */
    protected function _toHtml()
    {
        $this->_addCacheTags();
        $menuTree = $this->_categoryTree;
        if (!$this->getTemplate() || is_null($menuTree)) {
            throw new Exception("Top-menu renderer isn't fully configured.");
        }

        $includeFilePath = realpath(Mage::getBaseDir('design') . DS . $this->getTemplateFile());
        if (strpos($includeFilePath, realpath(Mage::getBaseDir('design'))) === 0 || $this->_getAllowSymlinks()) {
            $this->_templateFile = $includeFilePath;
        } else {
            throw new Exception('Not valid template file:' . $this->_templateFile);
        }
        return $this->render($menuTree, $menuTree->getLevel());
    }

    /**
     * Fetches template. If template has return statement, than its value is used and direct output otherwise.
     * @param Varien_Data_Tree_Node $menuTree
     * @param $childrenWrapClass
     * @return string
     */
    public function render(Varien_Data_Tree_Node $menuTree, $level)
    {
        ob_start();
        $html = include $this->_templateFile;
        $directOutput = ob_get_clean();

        if (is_string($html)) {
            return $html;
        } else {
            return $directOutput;
        }
    }

    /**
     * Recursively adds categories
     *
     * @param Mage_Catalog_Model_Resource_Category_Collection|array $categories
     * @param Varien_Data_Tree_Node $parentCategoryNode
     */
    protected function _addCategoriesToMenu($categories, $parentCategoryNode)
    {
        $categoryModel = Mage::getModel('catalog/category');
        foreach ($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }

            $nodeId = 'category-node-' . $category->getId();

            $categoryModel->setId($category->getId());

            $tree = $parentCategoryNode->getTree();
            $categoryData = array(
                'name' => $category->getName(),
                'id' => $nodeId,
                'url' => Mage::helper('catalog/category')->getCategoryUrl($category),
                'product_count' => $category->getProductCount(),
                'has_children' => $category->hasChildren()
            );
            $categoryNode = new Varien_Data_Tree_Node($categoryData, 'id', $tree, $parentCategoryNode);
            $parentCategoryNode->addChild($categoryNode);

            $subcategories = $category->getChildrenCategories();

            $this->_addCategoriesToMenu($subcategories, $categoryNode);
        }
    }

    /**
     * Add cache tags
     *
     * @return void
     */
    protected function _addCacheTags()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock) {
            $this->addCacheTag($parentBlock->getCacheTags());
        }
    }

    /**
     * Retrieve cache key data
     *
     * @return array
     */
    public function getCacheKeyInfo(){
        $shortCacheId = array(
            'TOPMENU',
            Mage::app()->getStore()->getId(),
            Mage::getDesign()->getPackageName(),
            Mage::getDesign()->getTheme('template'),
            Mage::getSingleton('customer/session')->getCustomerGroupId(),
            'template' => $this->getTemplate(),
            'name' => $this->getNameInLayout(),
            $this->getCurrentEntityKey()
        );
        $cacheId = $shortCacheId;

        $shortCacheId = array_values($shortCacheId);
        $shortCacheId = implode('|', $shortCacheId);
        $shortCacheId = md5($shortCacheId);

        $cacheId['entity_key'] = $this->getCurrentEntityKey();
        $cacheId['short_cache_id'] = $shortCacheId;

        return $cacheId;
    }

    /**
     * Retrieve current entity key
     *
     * @return int|string
     */
    public function getCurrentEntityKey(){
        if (null === $this->_currentEntityKey) {
            $this->_currentEntityKey = Mage::registry('current_entity_key')
                ? Mage::registry('current_entity_key') : Mage::app()->getStore()->getRootCategoryId();
        }
        return $this->_currentEntityKey;
    }
}
<?php

class Mageplaza_BetterBlog_Model_Observer
{
    public function generateSitemap($observer)
    {
        $object = $observer->getObject();
        $io = $observer->getIo();
        $storeId = $object->getStoreId();
        $date = Mage::getSingleton('core/date')->gmtDate('Y-m-d');

        /**
         * Generate blog categories sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/category/changefreq', $storeId);
        $priority = (string)Mage::getStoreConfig('sitemap/category/priority', $storeId);
        $collection = Mage::getResourceModel('mageplaza_betterblog/category_collection')
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1);

        $categories = new Varien_Object();
        $categories->setItems($collection);

        foreach ($categories->getItems() as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($object->filterUrl($item->getCategoryUrl())),
                $date,
                $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
        unset($collection);

        /**
         * Generate blog post sitemap
         */
        $changefreq = (string)Mage::getStoreConfig('sitemap/product/changefreq', $storeId);
        $priority = (string)Mage::getStoreConfig('sitemap/product/priority', $storeId);
        $collection = Mage::getResourceModel('mageplaza_betterblog/post_collection')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->setOrder('created_at', 'desc');
        $posts = new Varien_Object();
        $posts->setItems($collection);

        foreach ($posts->getItems() as $item) {
            $xml = sprintf(
                '<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
                htmlspecialchars($object->filterUrl($item->getPostUrl())),
                $date,
                $changefreq,
                $priority
            );
            $io->streamWrite($xml);
        }
    }


    /**
     * Add elements to Topmenu tree
     *
     * @param Varien_Event_Observer $observer
     */

    public function addMenuItems($observer){
        $menu = $observer->getMenu();
        /** @var $tree Varien_Data_Tree*/
        $tree = $menu->getTree();
        /** @var $categoryCollection Mageplaza_BetterBlog_Model_Category*/
        $categoryCollection = Mage::getModel('mageplaza_betterblog/category')
            ->getCollection()
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1);
        $menuelementNodeId = 'article_category_';
        foreach($categoryCollection as $menuItem) {
            if($menuItem->getLevel()=='1'){
                $node = new Varien_Data_Tree_Node(array(
                    'name'   => $menuItem->getName(),
                    'id'     => $menuelementNodeId . $menuItem->getId(),
                    'url'    => $menuItem->getCategoryUrl(), // point somewhere
                ), 'id', $tree, $menu);

                $menu->addChild($node);

                $parent_id = $menuItem->getEntityId();


                $action = Mage::app()->getFrontController()->getAction()->getFullActionName();
                foreach ($categoryCollection as $_menuItem){
                    if(isset($parent_id) && $_menuItem->getParentId() == $parent_id) {
                        $tree = $node->getTree();
                        $data = array(
                            'name' => $_menuItem->getName(),
                            'id' => $menuelementNodeId . $_menuItem->getId(),
                            'url' => $_menuItem->getCategoryUrl(),
                            'is_active' => ($action == 'sean_menucreator_menuelement_index' || $action == 'sean_menucreator_menuelement_view')
                        );

                        $menuelementNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
                        $node->addChild($menuelementNode);
                    }
                }
            }
        }
    }

    protected function _addMenuItems(){

    }

    /**
     * Adds items to top menu
     *
     * @param Varien_Event_Observer $observer
     */
    public function addItemsToTopmenu(Varien_Event_Observer $observer){
        $block = $observer->getEvent()->getBlock();
        $block->addCacheTag(Mageplaza_BetterBlog_Model_Category::CACHE_TAG);
        $categoryCollection = Mage::getModel('mageplaza_betterblog/category')
            ->getCollection()
            ->addStoreFilter(Mage::app()->getStore())
            ->addFieldToFilter('status', 1);
        $this->_addItemsToMenu($categoryCollection, $observer->getMenu(), $block);
    }

    /**
     * Recursively adds categories to top menu
     *
     * @param Varien_Data_Tree_Node_Collection|array $categories
     * @param Varien_Data_Tree_Node $parentCategoryNode
     * @param Mage_Page_Block_Html_Topmenu $menuBlock
     * @param bool $addTags
     */
    protected function _addItemsToMenu($categories, $parentCategoryNode, $menuBlock, $addTags = false)
    {
        $categoryModel = Mage::getModel('mageplaza_betterblog/category');
        foreach ($categories as $category) {
            if (!$category->getStatus()) {
                continue;
            }

            $nodeId = 'article-node-' . $category->getId();

            $categoryModel->setId($category->getId());
            if ($addTags) {
                $menuBlock->addModelTags($categoryModel);
            }

            $tree = $parentCategoryNode->getTree();
            $categoryData = array(
                'name' => $category->getName(),
                'id' => $nodeId,
                'url' => $category->getCategoryUrl(),
                'is_active' => false//$this->_isActiveMenuCategory($category)
            );
            $categoryNode = new Varien_Data_Tree_Node($categoryData, 'id', $tree, $parentCategoryNode);
            $parentCategoryNode->addChild($categoryNode);

            $subcategories = $category->getChildrenCategories();

            $this->_addItemsToMenu($subcategories, $categoryNode, $menuBlock, $addTags);
        }
    }

    /**
     * Checks whether category belongs to active category's path
     *
     * @param Varien_Data_Tree_Node $category
     * @return bool
     */
    protected function _isActiveMenuCategory($category)
    {
        $catalogLayer = Mage::getSingleton('catalog/layer');
        if (!$catalogLayer) {
            return false;
        }

        $currentCategory = $catalogLayer->getCurrentCategory();
        if (!$currentCategory) {
            return false;
        }

        $categoryPathIds = explode(',', $currentCategory->getPathInStore());
        return in_array($category->getId(), $categoryPathIds);
    }
}
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

    public function addMenuItems($observer){

        /**
         *  Add elements to Topmenu tree
         */

        $menu = $observer->getMenu();
        /** @var $tree Varien_Data_Tree*/
        $tree = $menu->getTree();
        /** @var $categoryCollection Mageplaza_BetterBlog_Model_Category*/
        $categoryCollection=Mage::getModel('mageplaza_betterblog/category')
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
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:55 PM
 */
class Stableflow_Company_Model_Observer extends Mage_Core_Model_Observer{

    public function addMenuItems($observer){
        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        $node = new Varien_Data_Tree_Node(array(
            'name'   => 'Companies',
            'id'     => 'companies-1',
            'url'    => Mage::getUrl('company'), // point somewhere
        ), 'id', $tree, $menu);

        $menu->addChild($node);

        //foreach ($collection as $category) {
            $tree = $node->getTree();
            $data = array(
                'name'   => 'Sellers',
                'id'     => 'seller-node-1',
                'url'    => Mage::getUrl('company'),
            );

            $subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
            $node->addChild($subNode);
        //$node->appendChild($subNode);
        //}
    }

}
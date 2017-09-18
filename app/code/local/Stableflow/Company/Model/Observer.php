<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 5:55 PM
 */
class Stableflow_Company_Model_Observer extends Mage_Core_Model_Observer
{
    public function addMenuItems($observer)
    {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        $node = new Varien_Data_Tree_Node(array(
            'name'   => 'Companies',
            'id'     => 'companies-1',
            'url'    => Mage::getUrl('company'), // point somewhere
        ), 'id', $tree, $menu);

        $menu->addChild($node);
        $tree = $node->getTree();

        $collection = array(
            array(
                'name'   => Mage::helper('company')->__('Seller'),
                'id'     => 'seller-node-1',
                'url'    => Mage::getUrl('company/index/list/company_type/1'),
            ),
            array(
                'name'   => 'Manufacturer',
                'id'     => 'seller-node-2',
                'url'    => Mage::getUrl('company/index/list/company_type/2'),
            )
        );

        foreach ($collection as $category) {
            $subNode = new Varien_Data_Tree_Node($category, 'id', $tree, $node);
            $node->addChild($subNode);
        //$node->appendChild($subNode);
        }
    }

    public function generateSiteMap()
    {
        try {
            Mage::getModel('company/generateSitemap')->generateXml();
        }catch(Mage_Core_Exception $e){
            Mage::log($e, 0,'site_map_error.log');
        }
    }

    public function updateOwner($observer)
    {
        $customer = $observer->getCustomer();
        Mage::getModel('company/owner')->addOwner($customer);
    }

    public function addToLog($observer)
    {
        $container = $observer->getUpdateData();
    }

    public function addButtonToGrid($observer)
    {
        $container = $observer->getBlock();
        if(null !== $container && $container->getType() == 'company/adminhtml_company_edit') {
            $data = array(
                'label'     => 'My button',
                'class'     => 'some-class',
                'onclick'   => 'setLocation(\' '  . Mage::getUrl('*/*', array('param' => 'value')) . '\')',
            );
            $container->addButton('my_button_identifier', $data);
        }

        return $this;
    }
}
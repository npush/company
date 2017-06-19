<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 11:36 AM
 */

require_once Mage::getModuleDir('controllers', 'Mage_Cms') . DS . 'IndexController.php';

class Stableflow_Log404_Cms_IndexController extends Mage_Cms_IndexController
{
    /**
     * Render CMS 404 Not found page and Log notification
     *
     * @param string $coreRoute
     */
    public function noRouteAction($coreRoute = null)
    {
        /** @var Stableflow_Log404_Helper_Data $helper */
        $helper = Mage::helper('sf_log404');
        $helper->log404Error();

        $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
        $this->getResponse()->setHeader('Status','404 File not found');

        $pageId = Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_NO_ROUTE_PAGE);
        if (!Mage::helper('cms/page')->renderPage($this, $pageId)) {
            $this->_forward('defaultNoRoute');
        }
    }

}
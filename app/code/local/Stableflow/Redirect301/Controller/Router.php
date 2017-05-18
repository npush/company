<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/17/17
 * Time: 12:48 PM
 */
class Stableflow_Redirect301_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard
{

    /**
     * init routes
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Stableflow_Redirect301_Controller_Router
     */
    public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('redirect301', $this);
        return $this;
    }

    /**
     * Validate and match entities and modify request
     *
     * @access public
     * @param Zend_Controller_Request_Http $request
     * @return bool
     */
    public function match(Zend_Controller_Request_Http $request)
    {

        //checking before even try to find out that current module
        //should use this router
        if (!$this->_beforeModuleMatch()) {
            return false;
        }

        $this->fetchDefault();

        $front = $this->getFront();

        $path = trim($request->getPathInfo(), '/');

        if ($path) {
            $p = explode('/', $path);
        } else {
            $p = explode('/', $this->_getDefaultPath());
        }

        if($p[0] == 'product'  && is_numeric($p[1])){
            $paramName = 'old_product_id';
        }

        if($p[0] == 'catalog' && is_numeric($p[1])){
            $paramName = 'old_catalog_id';
        }

        if($p[0] == 'productType' && is_numeric($p[1])){
            $paramName = 'old_product_type_id';
        }

        $realModule = 'Stableflow_Redirect301';
        $moduleName = 'redirect301';
        $controllerName = 'index';
        $actionName = 'redirect';

        $controllerClassName = $this->_validateControllerClassName($realModule, $controllerName);

        // instantiate controller class
        $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());

        $request->setModuleName($moduleName)
            ->setControllerName($controllerName)
            ->setActionName($actionName)
            ->setControllerModule($realModule)
            ->setParam($paramName, $p[1]);

        // dispatch action
        $request->setDispatched(true);
        $controllerInstance->dispatch($actionName);
        return true;
    }

}
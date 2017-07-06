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

        $returnStatus = false;

        if ($path) {
            $p = explode('/', $path);
        } else {
            $p = explode('/', $this->_getDefaultPath());
        }

        if($p[0] == 'product'  && is_numeric($p[1])){
            $paramName = 'old_product_id';
            $returnStatus = true;
        }

        if($p[0] == 'catalog' && is_numeric($p[1])){
            $paramName = 'old_catalog_id';
            $returnStatus = true;
        }

        if($p[0] == 'productType' && is_numeric($p[1])){
            $paramName = 'old_product_type_id';
            $returnStatus = true;
        }

        if($p[0] == 'article' && is_numeric($p[1])){
            $paramName = 'old_article_id';
            $returnStatus = true;
        }

        if(!$returnStatus){
            return $returnStatus;
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
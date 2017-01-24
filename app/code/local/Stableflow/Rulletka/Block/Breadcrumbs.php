<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/23/17
 * Time: 5:58 PM
 */

class Stableflow_Rulletka_Block_Breadcrumbs  extends Mage_Page_Block_Html_Breadcrumbs
{
    /**
     * Array of breadcrumbs
     *
     * array(
     *  [$index] => array(
     *                  ['label']
     *                  ['title']
     *                  ['link']
     *                  ['first']
     *                  ['last']
     *              )
     * )
     *
     * @var array
     */
    protected $_crumbs = null;

    public function __construct(){
        parent::__construct();
        $this->setTemplate('rulletka/breadcrumbs.phtml');
    }

    public function addCrumb($crumbName, $crumbInfo, $after = false){
        if($after){
            $this->_prepareArray($crumbInfo, array('label', 'title', 'link', 'first', 'last', 'readonly'));
            if(!is_array($this->_crumbs)){
                $this->_crumbs = array();
            }
            if(isset($this->_crumbs[$after])){
                $position = array_search($after, array_keys($this->_crumbs)) + 1;
                $this->_crumbs = array_slice($this->_crumbs, 0, $position, true) +
                    array($crumbName => $crumbInfo) +
                    array_slice($this->_crumbs, $position, count($this->_crumbs) - 1, true);
            }else{
                $this->_crumbs = array($crumbName => $crumbInfo) + $this->_crumbs;
            }
        }else{
            $this->_prepareArray($crumbInfo, array('label', 'title', 'link', 'first', 'last', 'readonly'));
            if((!isset($this->_crumbs[$crumbName])) || (!$this->_crumbs[$crumbName]['readonly'])){
                $this->_crumbs[$crumbName] = $crumbInfo;
            }
        }
        return $this;
    }

    protected function _toHtml(){
        $enabled = (bool)Mage::getStoreConfig('rulletka/rulletka_breadcrumbs/enabled');
        if(!$enabled) {
            return parent::_toHtml();
        }
        $this->prepareCrumbs();
        if(is_array($this->_crumbs)){
            reset($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['first'] = true;
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['last'] = true;
        }
        $this->assign('crumbs', $this->_crumbs);
        return parent::_toHtml();
    }

    /**
     * Check if product exists in the breadcrumbs
     * IF exists THEN get full path to product ELSE return default breadcrumbs
     *
     * @return array
     */
    protected function prepareCrumbs(){
        $crumbs = $this->_crumbs;
        $_helper = Mage::helper('rulletka/breadcrumbs');
        if($_helper->isItProductPage()  /*&& !$_helper->hasCurrentCategory()*/){
            $catPath = $_helper->getCategoryPath();
            foreach($crumbs as $_crumbName => $_crumbInfo){
                if(strstr($_crumbName, 'product')){
                    unset($crumbs[$_crumbName]);
                }
            }
            $crumbs += $catPath;
        }
        $this->_crumbs = $crumbs;
    }
}
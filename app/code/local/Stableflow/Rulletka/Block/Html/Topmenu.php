<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/24/17
 * Time: 2:34 PM
 */
class Stableflow_Rulletka_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu{

    /**
     * Returns array of menu item's classes
     *
     * @param Varien_Data_Tree_Node $item
     * @return array
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        $classes = array();

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        /*if ($item->getIsActive()) {
            $classes[] = 'active';
        }*/

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/6/17
 * Time: 12:15 PM
 */
class Stableflow_BlackIp_Block_Adminhtml_Widget_Grid_Column_Renderer_Google extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
        {
            $ip = $row->getData($this->getColumn()->getIndex());
            $html = '<p>'.$ip.'</p>';
            //$html .= '<button onclick="checkWhoIs('.'$ip'.'); return false">' . Mage::helper('sf_blackip')->__('Who Is') . '</button>';
            //$html .= '<a href="http://www.google.com/search?q=whois:+'.$ip.'" target="_blank">'.$ip.'</a>';
            return $html;
        }
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/28/17
 * Time: 11:26 AM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Renderer_SpentTime
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $html = $this->formatSeconds($row->getTimeSpent());
        return $html;
    }

    protected function formatSeconds($seconds)
    {
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;
        $time = sprintf('%u:%02u:%02u', $hours, $minutes, $seconds);
        return rtrim($time, '0');
    }
}
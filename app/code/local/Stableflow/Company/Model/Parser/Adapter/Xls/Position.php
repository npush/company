<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/9/17
 * Time: 3:13 PM
 */
class Stableflow_Company_Model_Parser_Adapter_Xls_Position
{
    protected $_position = null;
    protected $_page = null;

    /**
     *
     */
    public function __construct()
    {
        list($this->_page, $this->_position) = func_get_args();
    }

    public function getPosition()
    {
        return $this->_page.':'.$this->_position;
    }

    public function setPosition($position)
    {
        list($this->_page, $this->_position) = explode(':', $position);
    }

    public function getPage()
    {
        return $this->_page;
    }
}
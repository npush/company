<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:30 PM
 */
class Stableflow_Company_Model_Parser_Config_Settings implements ArrayAccess, Countable
{
    /**
     * Additional params
     * @var array
     */
    protected $_params = null;

    /**
     * File type (csv, xml, xls, etc...)
     * @var string
     */
    protected $_type = null;

    protected $_currentSheet = array();

    /**
     * Sheets settings
     * @var array
     */
    protected $_sheets = array();

    protected $_defconf = array(
        'field_map' => array(
            'price'             => null,
            'price_internal'    => null,
            'price_wholesale'   => null,
            'item_price'        => null,
            'box'               => null,
            'name'              => null,
            'code'              => null,
            'qty_in_stock'      => null,
        ),
        'settings' => array(
            'header_row'        => null,
            'start_row'         => null,
            'manufacturer'      => null,
        ),
        'settings_currency' => array(
            'currency'          => null,
            'change_currency'   => null,
        )
    );

    /**
     * First sheet Id. Default sheet Id 0
     * @var int
     */
    protected $_firstSheet = 0;


    public function __construct()
    {
        $args = func_get_args();
        if (empty($args[0])) {
            $this->_init();
        }else {
            $this->setSettings($args[0]);
        }
    }

    /**
     * Set default settings
     */
    protected function _init()
    {
        $this->_sheets[$this->_firstSheet] = $this->_defconf;
        $this->_type = 'csv';
    }

    /**
     * Assigns a value to the specified offset
     *
     * @param string $offset The offset to assign the value to
     * @param mixed  $value The value to set
     * @access public
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_sheets[] = $value;
        } else {
            $this->_sheets[$offset] = $value;
        }
    }

    /**
     * Whether or not an offset exists
     *
     * @param string $offset An offset to check for
     * @access public
     * @return boolean
     */
    public function offsetExists($offset) {
        return isset($this->_sheets[$offset]);
    }

    /**
     * Unsets an offset
     *
     * @param string $offset The offset to unset
     * @access public
     */
    public function offsetUnset($offset) {
        unset($this->_sheets[$offset]);
    }

    /**
     * Returns the value at specified offset
     *
     * @param string $offset The offset to retrieve
     * @access public
     * @return mixed
     */
    public function offsetGet($offset) {
        return isset($this->_sheets[$offset]) ? $this->_sheets[$offset] : null;
    }

    public function count()
    {
        return count($this->_sheets);
    }

    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get first sheet ID
     * @return int
     */
    public function getFirstSheetId()
    {
        return $this->_firstSheet;
    }

    /**
     * @param int $idx Sheet Id
     * @return array
     */
    public function getSheetSettings($idx)
    {
        if(!is_null($idx) && array_key_exists($idx, $this->_sheets)) {
            return $this->_sheets[$idx];
        }
        return null;
    }

    public function getSheet($idx)
    {
        if(!is_null($idx) && array_key_exists($idx, $this->_sheets))
        $this->_currentSheet = $this->_sheets[$idx];
        return $this;
    }

    /**
     * Set Setting
     * @param array $settings
     * @return null|Stableflow_Company_Model_Parser_Config_Settings
     */
    public function setSettings($settings)
    {
        if(is_array($settings)) {
            $this->_firstSheet = $settings[0]['index'];
            $this->_type = $settings[0]['type'];
            foreach($settings as $_tab){
                unset($_tab['type']);
                $index = $_tab['index'];
                unset($_tab['index']);
                $this->_sheets[$index] = array_merge($this->_defconf, $_tab);
            }
            $this->getSheet($this->_firstSheet);
            return $this;
        }
        return null;
    }

    public function getSheetsCont()
    {
        return count($this->_sheets);
    }

    /**
     * Return sheet Id`s array
     * @return array
     */
    public function getSheetsIds()
    {
        return array_keys($this->_sheets);
    }

    public function getAllSheets()
    {
        return $this->_sheets;
    }

    /**
     * @return array | bool
     */
    public function getFieldMap()
    {
        return $this->_currentSheet['field_map'];
    }

    /**
     * @return array | bool
     */
    public function getStartRow()
    {
        return $this->_currentSheet['settings']['start_row'];
    }

    /**
     * @return array | bool
     */
    public function getHeaderRow()
    {
         return $this->_currentSheet['settings']['header_row'];
    }

    /**
     * @return array | bool
     */
    public function getCurrency()
    {
        return array_keys($this->_currentSheet['settings_currency'], array('currency', 'change_currency'));
    }

    /**
     * @return array | bool
     */
    public function getManufacturer()
    {
        return $this->_currentSheet['settings']['manufacturer'];
    }

    public function getStartPosition()
    {
        return $this->getFirstSheetId() . ':' . $this->getStartRow();
    }
}
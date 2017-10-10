<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:30 PM
 */
class Stableflow_Company_Model_Parser_Config_Settings
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
     * @param int $idx Sheet Id
     * @return array | bool
     */
    public function getFieldMap($idx)
    {
        return $this->getSheetSettings($idx)['field_map'];
    }

    /**
     * @param int $idx Sheet Id
     * @return array | bool
     */
    public function getStartRow($idx)
    {
        return $this->getSheetSettings($idx)['settings']['start_row'];
    }

    /**
     * @param int $idx Sheet Id
     * @return array | bool
     */
    public function getHeaderRow($idx)
    {
         return $this->getSheetSettings($idx)['settings']['header_row'];
    }

    /**
     * @param int $idx Sheet Id
     * @return array | bool
     */
    public function getCurrency($idx)
    {
        return array_keys($this->getSheetSettings($idx)['settings_currency'], array('currency', 'change_currency'));
    }

    /**
     * @param int $idx Sheet Id
     * @return array | bool
     */
    public function getManufacturer($idx)
    {
        return $this->getSheetSettings($idx)['settings']['manufacturer'];
    }
}
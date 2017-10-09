<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:30 PM
 */
class Stableflow_Company_Model_Parser_Config_Settings extends Varien_Object
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
     * current sheet settings
     * @var array
     */
    protected $_sheetSettings;

    /**
     * First sheet Num. Default sheet 0
     * @var int
     */
    protected $_firstSheet = 0;


    public function __construct()
    {
        $args = func_get_args();
        if (empty($args[0])) {
            $this->setSettings();
        }else {
            $this->setSettings($args[0]);
        }
        $this->_construct();
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getFirstSheetNum()
    {
        return $this->_firstSheet;
    }

    /**
     * @param $idx
     * @return array
     */
    public function getSheetSettings($idx = null)
    {
        if(!is_null($idx) && array_key_exists($idx, $this->_sheets)) {
            $this->_sheetSettings = $this->_sheets[$this->_firstSheet];
        }elseif(!is_null($idx) && !array_key_exists($idx, $this->_sheets)){
            // error!!!
        }
        return $this->_sheetSettings;
    }

    /**
     * Set Setting
     * @param null $settings
     * @return $this
     */
    public function setSettings($settings = null)
    {
        if(is_null($settings)){
            $this->_sheets[$this->_firstSheet] = $this->_defconf;
            $this->_sheetSettings = $this->_sheets[$this->_firstSheet];
        }
        if(is_array($settings)) {
            $this->_firstSheet = $settings[0]['index'];
            $this->_type = $settings[0]['type'];
            foreach($settings as $_tab){
                unset($_tab['type']);
                $index = $_tab['index'];
                unset($_tab['index']);
                $this->_sheets[$index] = array_merge($this->_defconf, $_tab);
            }
            $this->_sheetSettings = $this->_sheets[$this->_firstSheet];
        }
        return $this;
    }

    public function getSheetsCont()
    {
        return count($this->_sheets);
    }

    /**
     * Return sheet numbers array
     * @return array
     */
    public function getSheetsNumbers()
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
        return $this->_sheetSettings['field_map'];
    }

    public function getStartRow()
    {
        return $this->_sheetSettings['settings']['start_row'];
    }

    public function getHeaderRow()
    {
         return $this->_sheetSettings['settings']['header_row'];
    }

    public function getCurrency()
    {
        return array_keys($this->_sheetSettings['settings_currency'], array('currency', 'change_currency'));
    }

    public function getManufacturer()
    {
        return $this->_sheetSettings['settings']['manufacturer'];
    }
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/21/17
 * Time: 11:39 AM
 */
class Stableflow_Company_Model_Parser_Uploader extends Mage_Core_Model_File_Uploader
{

    const PARSER_PRICE_BASE_DIR = 'pricelists';

    protected $_allowedMimeTypes = array(
        'csv' => 'text/csv',
        'xml' => 'text/xml',
        'xls' => array(
            'application/vnd.ms-excel',
            'application/excel',
            'application/x-excel',
            'application/x-msexcel'
        ),
        'xslx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'

    );
    const DEFAULT_FILE_TYPE = 'application/octet-stream';

    /**
     * Initiate uploader default settings
     */
    public function init()
    {
        $this->setAllowRenameFiles(true);
        $this->setAllowCreateFolders(true);

        // Set the file upload mode
        // false -> get the file directly in the specified folder
        // true -> get the file in the product like folders
        //	(file.jpg will go in something like /media/f/i/file.jpg)
        $this->setFilesDispersion(true);
        //$this->setAllowedExtensions(array('jpg','jpeg','gif','png'));
        $this->setAllowedExtensions(array_keys($this->_allowedMimeTypes));
        $this->setValidMimeTypes();
        $this->_uploadType = self::SINGLE_STYLE;
    }

    public function save($newFileName = null)
    {
        $destinationFolder = Mage::getBaseDir('media'). DS . self::PARSER_PRICE_BASE_DIR . DS;
        return parent::save($destinationFolder, $newFileName = null);
    }

    /**
     * Validate uploaded file by type and etc.
     */
    protected function _validateFile()
    {
        if ($this->_fileExists === false) {
            return;
        }

        //is file extension allowed
        if (!$this->checkAllowedExtension($this->getFileExtension())) {
            throw new Exception('Disallowed file type.');
        }

        /*
         * Validate MIME-Types.
         */
        if (!$this->checkMimeType($this->_validMimeTypes)) {
            throw new Exception('Invalid MIME type.');
        }

        //run validate callbacks
        foreach ($this->_validateCallbacks as $params) {
            if (is_object($params['object']) && method_exists($params['object'], $params['method'])) {
                $params['object']->{$params['method']}($this->_file['tmp_name']);
            }
        }
    }

    /**
     * Used to check if uploaded file mime type is valid or not
     *
     * @param array $validTypes
     * @access public
     * @return bool
     */
    public function checkMimeType($validTypes = array())
    {
        try {
            if (count($validTypes) > 0) {
                $validator = new Zend_Validate_File_MimeType($validTypes);
                return $validator->isValid($this->_file['tmp_name']);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Set valid MIME-types.
     *
     * @param array $mimeTypes
     * @return Varien_File_Uploader
     */
    public function setValidMimeTypes($mimeTypes = array())
    {
        $this->_validMimeTypes = array();
        foreach ((array) $mimeTypes as $mimeType) {
            $this->_validMimeTypes[] = $mimeType;
        }
        return $this;
    }
}
<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 12:40 PM
 */
class Stableflow_Company_Model_Parser_Log_Message
{
    const MESSAGE_ERROR     = 'error'; //1;
    const MESSAGE_WARNING   = 'warning'; //2;
    const MESSAGE_SUCCESS   = 'success'; //3;

    protected function _factory($rowData, $statusCode, $type)
    {
        switch (strtolower($type)) {
            case self::MESSAGE_ERROR :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Error($rowData, $statusCode);
                break;
            case self::MESSAGE_WARNING :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Warning($rowData, $statusCode);
                break;
            case self::MESSAGE_SUCCESS :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($rowData, $statusCode);
                break;
            default:
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($rowData, $statusCode);
                break;
        }
        return $message;
    }

    /**
     * Error message instance
     * @param $rowData array
     * @param $statusCode string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function error($rowData, $statusCode)
    {
        return $this->_factory($rowData, $statusCode, self::MESSAGE_ERROR);
    }

    /**
     * Error message instance
     * @param $rowData array
     * @param $statusCode string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function warning($rowData, $statusCode)
    {
        return $this->_factory($rowData, $statusCode, self::MESSAGE_WARNING);
    }

    /**
     * Error message instance
     * @param $rowData array
     * @param $statusCode string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function success($rowData, $statusCode)
    {
        return $this->_factory($rowData, $statusCode, self::MESSAGE_SUCCESS);
    }
}
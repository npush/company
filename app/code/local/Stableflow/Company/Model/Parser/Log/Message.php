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

    protected function _factory($code, $type)
    {
        switch (strtolower($type)) {
            case self::MESSAGE_ERROR :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Error($code);
                break;
            case self::MESSAGE_WARNING :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Warning($code);
                break;
            case self::MESSAGE_SUCCESS :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($code);
                break;
            default:
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($code);
                break;
        }
        return $message;
    }

    /**
     * Error message instance
     * @param $code array
     * @return Stableflow_Company_Model_Parser_Log_Message_Error
     */
    public function error($code)
    {
        return $this->_factory($code, self::MESSAGE_ERROR);
    }

    /**
     * Error message instance
     * @param $code array
     * @return Stableflow_Company_Model_Parser_Log_Message_Warning
     */
    public function warning($code)
    {
        return $this->_factory($code, self::MESSAGE_WARNING);
    }

    /**
     * Error message instance
     * @param $code array
     * @return Stableflow_Company_Model_Parser_Log_Message_Success
     */
    public function success($code)
    {
        return $this->_factory($code, self::MESSAGE_SUCCESS);
    }
}
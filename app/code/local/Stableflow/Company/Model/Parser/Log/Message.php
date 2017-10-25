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

    protected function _factory($data, $type)
    {
        switch (strtolower($type)) {
            case self::MESSAGE_ERROR :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Error($data);
                break;
            case self::MESSAGE_WARNING :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Warning($data);
                break;
            case self::MESSAGE_SUCCESS :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($data);
                break;
            default:
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($data);
                break;
        }
        return $message;
    }

    /**
     * Error message instance
     * @param $data array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function error($data)
    {
        return $this->_factory($data, self::MESSAGE_ERROR);
    }

    /**
     * Error message instance
     * @param $data array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function warning($data)
    {
        return $this->_factory($data, self::MESSAGE_WARNING);
    }

    /**
     * Error message instance
     * @param $data array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function success($data)
    {
        return $this->_factory($data, self::MESSAGE_SUCCESS);
    }
}
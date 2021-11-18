<?php
namespace Application\Admin\Components;


trait ErrorManager
{

    /**
     * Author:Robert
     *
     * @var
     */
    protected $errorMessage = '';

    /**
     * Author:Robert
     *
     * @var
     */
    protected $errorCode = 0;

    /**
     * Author:Robert
     *
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Author:Robert
     *
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Author:Robert
     *
     * @param $message
     * @param $code
     */
    public function setError($message, $code)
    {
        $this->errorMessage = $message;
        $this->errorCode = $code;
    }

}
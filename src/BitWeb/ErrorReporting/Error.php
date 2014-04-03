<?php

namespace BitWeb\ErrorReporting;

class Error
{
    /**
     * @var ErrorInfo[]
     */
    protected $errors = array();

    /**
     * @var ErrorMeta
     */
    protected $meta;

    function __construct($errors, $meta)
    {
        $this->errors = $errors;
        $this->meta = $meta;
    }

    /**
     * @param ErrorInfo[] $errors
     * @return self
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param ErrorInfo $errorInfo
     * @return $this
     */
    public function addError(ErrorInfo $errorInfo)
    {
        $this->errors[] = $errorInfo;
        return $this;
    }

    /**
     * @return ErrorInfo[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param ErrorMeta $meta
     * @return self
     */
    public function setMeta(ErrorMeta $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return ErrorMeta
     */
    public function getMeta()
    {
        return $this->meta;
    }
} 
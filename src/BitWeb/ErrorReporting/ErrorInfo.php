<?php

namespace BitWeb\ErrorReporting;


class ErrorInfo
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $tracking;

    /**
     * @var string
     */
    protected $severity;

    function __construct($class, $title, $tracking, $severity = null)
    {
        $this->class = $class;
        $this->severity = $severity;
        $this->title = $title;
        $this->tracking = $tracking;
    }

    /**
     * @param string $class
     * @return self
     */
    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $severity
     * @return self
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $tracking
     * @return self
     */
    public function setTracking($tracking)
    {
        $this->tracking = $tracking;
        return $this;
    }

    /**
     * @return string
     */
    public function getTracking()
    {
        return $this->tracking;
    }
} 
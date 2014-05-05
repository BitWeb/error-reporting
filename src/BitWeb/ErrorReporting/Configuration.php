<?php


namespace BitWeb\ErrorReporting;


use BitWeb\Stdlib\AbstractConfiguration;

class Configuration extends AbstractConfiguration
{
    protected $emails;
    protected $subject;
    protected $fromAddress;
    protected $botList;
    protected $ignore404;
    protected $ignoreBot404;
    protected $ignorableExceptions;


    /**
     * @param mixed $botList
     */
    public function setBotList(array $botList)
    {
        $this->botList = $botList;
    }

    /**
     * @return mixed
     */
    public function getBotList()
    {
        return $this->botList;
    }

    /**
     * @param array $emails
     */
    public function setEmails(array $emails)
    {
        $this->emails = $emails;
    }

    /**
     * @return array
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param mixed $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * @param array $ignorableExceptions
     */
    public function setIgnorableExceptions(array $ignorableExceptions)
    {
        $this->ignorableExceptions = $ignorableExceptions;
    }

    /**
     * @return mixed
     */
    public function getIgnorableExceptions()
    {
        return $this->ignorableExceptions;
    }

    /**
     * @param mixed $ignore404
     */
    public function setIgnore404($ignore404)
    {
        $this->ignore404 = $ignore404;
    }

    /**
     * @return mixed
     */
    public function getIgnore404()
    {
        return $this->ignore404;
    }

    /**
     * @param mixed $ignoreBot404
     */
    public function setIgnoreBot404($ignoreBot404)
    {
        $this->ignoreBot404 = $ignoreBot404;
    }

    /**
     * @return mixed
     */
    public function getIgnoreBot404()
    {
        return $this->ignoreBot404;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

} 
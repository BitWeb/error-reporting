<?php
namespace BitWeb\ErrorReporting;

use BitWeb\Stdlib\AbstractConfiguration;

class Configuration extends AbstractConfiguration
{
    protected $errorReportingLevel = 'E_ALL';
    protected $emails = array();
    protected $subject = 'Errors';
    protected $fromAddress = '';
    protected $botList = array();
    protected $ignore404 = false;
    protected $ignoreBot404 = false;
    protected $ignorableExceptions = array('ErrorException');
    protected $ignorablePaths = array();

    /**
     * The level of error reporting.
     * @var string $errorReportingLevel
     */
    public function setErrorReportingLevel($errorReportingLevel) {
        $this->errorReportingLevel = $errorReportingLevel;
    }

    /**
     * @return string
     */
    public function getErrorReportingLevel() {
        return $this->errorReportingLevel;
    }

    /**
     * Defines bots.
     * @var array $botList
     */
    public function setBotList(array $botList)
    {
        $this->botList = $botList;
    }

    /**
     *
     * @return array
     */
    public function getBotList()
    {
        return $this->botList;
    }

    /**
     * An array of emails the error report is sent to.
     * @var array $emails
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
     * Address where the message is sent from.
     * @var String $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return string
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * Exceptions to ignore.
     * @var array $ignorableExceptions
     */
    public function setIgnorableExceptions(array $ignorableExceptions)
    {
        $this->ignorableExceptions = $ignorableExceptions;
    }

    /**
     * @return array
     */
    public function getIgnorableExceptions()
    {
        return $this->ignorableExceptions;
    }

    /**
     * Are 404 errors ignored?
     * @var boolean $ignore404
     */
    public function setIgnore404($ignore404)
    {
        $this->ignore404 = $ignore404;
    }

    /**
     * @return boolean
     */
    public function getIgnore404()
    {
        return $this->ignore404;
    }

    /**
     * Are bot 404 errors ignored?
     * @var boolean $ignoreBot404
     */
    public function setIgnoreBot404($ignoreBot404)
    {
        $this->ignoreBot404 = $ignoreBot404;
    }

    /**
     * @return boolean
     */
    public function getIgnoreBot404()
    {
        return $this->ignoreBot404;
    }

    /**
     * The subject of the message being sent.
     * @var string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Paths to ignore.
     * @var array $ignorablePaths
     */
    public function setIgnorablePaths(array $ignorablePaths) {
        $this->ignorablePaths = $ignorablePaths;
    }

    /**
     * @return array
     */
    public function getIgnorablePaths() {
        return $this->ignorablePaths;
    }

} 
<?php

namespace BitWeb\ErrorReporting;


class ErrorMeta
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $postData;

    /**
     * @var array
     */
    protected $getData;

    /**
     * @var array
     */
    protected $sessionData;

    /**
     * @var array
     */
    protected $serverData;

    /**
     * @var string
     */
    protected $referrer;

    /**
     * @var \DateTime
     */
    protected $requestTime;

    /**
     * @var float
     */
    protected $requestDuration;

    /**
     * @param array $getData
     * @return self
     */
    public function setGetData(array $getData)
    {
        $this->getData = $getData;
        return $this;
    }

    /**
     * @return array
     */
    public function getGetData()
    {
        return $this->getData;
    }

    /**
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param array $postData
     * @return self
     */
    public function setPostData(array $postData)
    {
        $this->postData = $postData;
        return $this;
    }

    /**
     * @return array
     */
    public function getPostData()
    {
        return $this->postData;
    }

    /**
     * @param string $referrer
     * @return self
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     * @param float $requestDuration
     * @return self
     */
    public function setRequestDuration($requestDuration)
    {
        $this->requestDuration = $requestDuration;
        return $this;
    }

    /**
     * @return float
     */
    public function getRequestDuration()
    {
        return $this->requestDuration;
    }

    /**
     * @param \DateTime $requestTime
     * @return self
     */
    public function setRequestTime(\DateTime $requestTime)
    {
        $this->requestTime = $requestTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * @param array $serverData
     * @return self
     */
    public function setServerData(array $serverData)
    {
        $this->serverData = $serverData;
        return $this;
    }

    /**
     * @return array
     */
    public function getServerData()
    {
        return $this->serverData;
    }

    /**
     * @param array $sessionData
     * @return self
     */
    public function setSessionData(array $sessionData = null)
    {
        $this->sessionData = $sessionData;
        return $this;
    }

    /**
     * @return array
     */
    public function getSessionData()
    {
        return $this->sessionData;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $userAgent
     * @return self
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
} 
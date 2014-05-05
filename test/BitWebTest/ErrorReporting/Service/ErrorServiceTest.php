<?php

namespace BitWebTest\ErrorReporting\Service;


use BitWeb\ErrorReporting\Configuration;
use BitWeb\ErrorReporting\Error;
use BitWeb\ErrorReporting\Service\ErrorService;

class ErrorServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    public $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration(include __DIR__ . '/../TestAsset/config.php');
    }

    protected static function getMethod($class, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    public function testCanCreate()
    {
        $this->assertInstanceOf(ErrorService::class, new ErrorService($this->configuration));
    }

    public function testHasReceiverEmails()
    {
        $service = new ErrorService($this->configuration);
        $this->assertEquals(true, $service->hasReceiverEmails());
    }

    public function testIgnoreBot404()
    {
        $service = new ErrorService($this->configuration);
        $this->assertEquals(false, $service->ignoreBot404());
    }

    public function testIgnore404()
    {
        $service = new ErrorService($this->configuration);
        $this->assertEquals(false, $service->ignore404());
    }

    public function testStartErrorHandling()
    {
        $service = new ErrorService($this->configuration);
        $service->startErrorHandling(123);
        $this->assertEquals(123, \PHPUnit_Framework_Assert::readAttribute($service, 'startTime'));

        $service2 = new ErrorService($this->configuration);
        $service2->startErrorHandling();
        $this->assertInternalType("float", \PHPUnit_Framework_Assert::readAttribute($service2, 'startTime'));
    }

    public function testAddPhpError()
    {
        $error = new \ErrorException('test', 0, 1, null, null);
        $service = new ErrorService($this->configuration);
        $service->startErrorHandling();
        $service->addPhpError(1, 'test',null,null);
        $this->assertEquals($error, $service->errors[0]);
    }

    public function testGetErrorReportMetaData()
    {
        $service = new ErrorService($this->configuration);
        $service->startErrorHandling();
        trigger_error("Cannot divide by zero", E_USER_ERROR);
        $this->assertInstanceOf(Error::class, $service->getErrorReportMetaData());
    }

    public function testEndErrorHandling()
    {
        $service = new ErrorService($this->configuration);
        $service->startErrorHandling();
        trigger_error("Cannot divide by zero", E_USER_ERROR);
//        $service->endErrorHandling();
    }

}
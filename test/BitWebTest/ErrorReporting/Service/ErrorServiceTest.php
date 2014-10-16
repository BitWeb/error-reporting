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
        $this->assertFalse($service->hasReceiverEmails());
    }

    public function testIgnoreBot404()
    {
        $service = new ErrorService($this->configuration);
        $this->assertFalse($service->ignoreBot404());
        $this->configuration->setIgnoreBot404(true);
        $this->assertTrue($service->ignoreBot404());
    }

    public function testIgnore404()
    {
        $service = new ErrorService($this->configuration);
        $this->assertFalse($service->ignore404());
        $this->configuration->setIgnore404(true);
        $this->assertTrue($service->ignore404());
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
        $error = new \ErrorException('test', 0, 1);
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

    public function testEndErrorHandlingWithFatal()
    {
//        $serviceMock = $this->getMock(ErrorService::class, array(), array($this->configuration));
//        $serviceMock->expects($this->any())->method('endErrorHandling')->will($this->returnValue(true));
//        $serviceMock->startErrorHandling();
//        trigger_error("Cannot divide by zero", E_ALL);
//        $serviceMock->endErrorHandlingWithFatal();

//        $service->endErrorHandling();
    }

    public function testEndErrorHandling()
    {
//        $this->configuration->setEmails(array('kristjan.andresson@bitweb.ee'));
//        $service = new ErrorService($this->configuration);
//        $service->startErrorHandling();
//        trigger_error("Cannot divide by zero", E_USER_ERROR);
//        $service->endErrorHandling();
//        $this->assertEquals(array(), $service->errors);
//        $this->assertNull(\PHPUnit_Framework_Assert::readAttribute($service, 'startTime'));

    }

    public function testIsBotRequest()
    {
        $this->configuration->setBotList([
            'AhrefsBot',
            'bingbot',
            'Ezooms',
            'Googlebot',
            'Mail.RU_Bot',
            'YandexBot',
        ]);

        $service = new ErrorService($this->configuration);
        $this->assertFalse($service->isBotRequest());
        $_SERVER['HTTP_USER_AGENT'] = 'Googlebot';
        $this->assertTrue($service->isBotRequest());
    }

    public function testHasOnlyIgnorableExceptions()
    {
        $service = new ErrorService($this->configuration);
        $method = self::getMethod(ErrorService::class, 'hasOnlyIgnorableExceptions');
        $this->assertTrue($method->invokeArgs($service, []));
        $this->configuration->setIgnorableExceptions(array('TestException'));
        $service = new ErrorService($this->configuration);
        $service->startErrorHandling();
        trigger_error("Fatal error", E_USER_NOTICE);
        $this->assertFalse($method->invokeArgs($service, []));

    }

    public function testIsIgnorablePath()
    {
        $this->configuration->setIgnorablePaths([
            'wp-admin',
            'wp-login'
        ]);
        $service = new ErrorService($this->configuration);
        $_SERVER['REQUEST_URI'] = '/not-ignorable';
        $this->assertFalse($service->isIgnorablePath());
        $_SERVER['REQUEST_URI'] = '/wp-login';
        $this->assertTrue($service->isIgnorablePath());
    }

    public function testRestoreDefaultErrorHandling()
    {
        $service = new ErrorService($this->configuration);
        $this->assertTrue($service->restoreDefaultErrorHandling());

    }

}
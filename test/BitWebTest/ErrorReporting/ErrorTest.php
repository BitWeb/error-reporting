<?php

namespace BitWebTest\ErrorReporting;

use BitWeb\ErrorReporting\Error;
use BitWeb\ErrorReporting\ErrorInfo;
use BitWeb\ErrorReporting\ErrorMeta;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreate()
    {
        $this->assertInstanceof(Error::class, new Error([new ErrorInfo('class', 'title', 'tracking')], new ErrorMeta()));
    }

    public function testSettersAndGetters()
    {
        $errorInfo = new ErrorInfo('class', 'title', 'tracking');
        $errorMeta = new ErrorMeta();

        $error = new Error([$errorInfo], $errorMeta);

        $this->assertEquals([$errorInfo], $error->getErrors());
        $this->assertEquals($errorMeta, $error->getMeta());

        $newErrorInfo = new ErrorInfo('class', 'title', 'tracking');
        $newErrorMeta = new ErrorMeta();

        $error->setErrors([$newErrorInfo]);
        $error->setMeta($newErrorMeta);

        $this->assertEquals([$newErrorInfo], $error->getErrors());
        $this->assertEquals($newErrorMeta, $error->getMeta());
    }

    public function testAddError()
    {
        $errorInfo = new ErrorInfo('class', 'title', 'tracking');
        $errorMeta = new ErrorMeta();
        $newErrorInfo = new ErrorInfo('another class', 'another title', 'another tracking');

        $error = new Error([$errorInfo], $errorMeta);

        $error->addError($newErrorInfo);

        $this->assertTrue(is_array($error->getErrors()));
        $this->assertEquals($errorInfo, $error->getErrors()[0]);
        $this->assertEquals($newErrorInfo, $error->getErrors()[1]);
    }
}
 
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
}
 
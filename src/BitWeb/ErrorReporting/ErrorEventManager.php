<?php

namespace BitWeb\ErrorReporting;
/**
 * Created by PhpStorm.
 * User: priit
 * Date: 6/25/14
 * Time: 4:07 PM
 */

interface ErrorEventManager {

    public function trigger( $event, $target = null, $argv = array(), $callback = null);

    public function attach( $event, $function);

}
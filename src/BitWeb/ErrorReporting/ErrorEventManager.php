<?php

namespace BitWeb\ErrorReporting;

interface ErrorEventManager
{
    public function trigger($event, $target = null, $argv = [], $callback = null);

    public function attach($event, $function);
}

<?php

namespace webheads\core;

class CoreException extends \Exception
{
	public function __construct($code = 0, $message = null)
    {
        parent::__construct($message, $code);
    }
}
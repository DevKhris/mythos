<?php

namespace Mythos\Exceptions;

use Exception;

class TemplateNotFoundException Extends Exception
{
    /**
     * Exception message.
     */
    protected $message = 'File not found or template not valid';

    /**
     * Custom string representation.
     */
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

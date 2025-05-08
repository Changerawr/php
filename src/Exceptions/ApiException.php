<?php

namespace Changerawr\Exceptions;

use Exception;

/**
 * Base exception for all API-related errors.
 */
class ApiException extends Exception
{
    /**
     * @var mixed|null
     */
    protected $details;

    /**
     * Create a new API exception.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @param mixed|null $details
     */
    public function __construct($message = '', $code = 0, \Throwable $previous = null, $details = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * Get additional error details.
     *
     * @return mixed|null
     */
    public function getDetails()
    {
        return $this->details;
    }
}
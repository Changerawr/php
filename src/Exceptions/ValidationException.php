<?php

namespace Changerawr\Exceptions;

/**
 * Exception thrown when validation fails.
 */
class ValidationException extends ApiException
{
    /**
     * Get validation errors.
     *
     * @return array|null
     */
    public function getValidationErrors(): ?array
    {
        return $this->getDetails();
    }
}
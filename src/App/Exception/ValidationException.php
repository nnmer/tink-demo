<?php

namespace App\Exception;

use Symfony\Component\Validator\Exception\ValidatorException;

class ValidationException extends ValidatorException
{
    /** @var int $code */
    protected $code    = 400;

    /** @var string $message */
    protected $message = 'Validation failed';

    /** @var array|string */
    protected $errors;

    public function __construct($errors) {
        parent::__construct($this->message, $this->code);
        $this->errors = $errors;
    }

    /**
     * @return array|string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function toArray()
    {

        if (is_object($this->errors)) {
            $errors = [];
            foreach ($this->errors as $error) {
                $errors[] = [
                    'invalidValue' => $error->getInvalidValue(),
                    'propertyPath' => $error->getPropertyPath(),
                    'error' => $error->getMessage(),
                ];
            }
        } else {
            $errors = $this->errors;
        }

        return [
            'code' => $this->code,
            'message' => $this->message,
            'error' => $errors
        ];
    }
}
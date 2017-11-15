<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException as BaseNotFoundHttpException;

class NotFoundHttpException extends BaseNotFoundHttpException
{
    public function __construct($message = null, \Exception $previous = null, $code = 404)
    {
        parent::__construct( $message, $previous, $code);
    }
}
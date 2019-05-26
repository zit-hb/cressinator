<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormNotValidException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('Form not valid');
    }
}

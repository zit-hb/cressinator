<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormNotFoundException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('Form not found');
    }
}

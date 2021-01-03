<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormNotFoundException extends BadRequestHttpException
{
    public function __construct(FormInterface $form)
    {
        parent::__construct('Form not found (' . $form->getName() . ')');
    }
}

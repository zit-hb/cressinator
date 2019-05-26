<?php

namespace App\Traits;

use App\Exception\FormNotFoundException;
use App\Exception\FormNotValidException;
use Symfony\Component\Form\FormInterface;

trait FormControllerTrait
{
    /**
     * Often used combination to check if a form is submitted and valid
     * @param FormInterface $form
     * @throws FormNotFoundException if form not found
     * @throws FormNotValidException if form not valid
     */
    public function checkForm(FormInterface $form)
    {
        if (!$form->isSubmitted()) {
            throw new FormNotFoundException();
        }

        if (!$form->isValid()) {
            throw new FormNotValidException();
        }
    }
}

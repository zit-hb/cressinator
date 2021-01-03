<?php

namespace App\Service\Api;

use App\Entity\EntityInterface;
use App\Exception\FormNotFoundException;
use App\Exception\FormNotValidException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FormService
{
    /** @var Request */
    private $request;

    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * FormService constructor.
     * @param RequestStack $requestStack
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(RequestStack $requestStack, FormFactoryInterface $formFactory)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->formFactory = $formFactory;
    }

    /**
     * Often used combination to check if a form is submitted and valid
     * @param FormInterface $form
     * @throws FormNotFoundException if form not found
     * @throws FormNotValidException if form not valid
     */
    public function checkForm(FormInterface $form)
    {
        if (!$form->isSubmitted()) {
            throw new FormNotFoundException($form);
        }

        if (!$form->isValid()) {
            throw new FormNotValidException($form);
        }
    }

    /**
     * Read in entity through request and check validity
     * @param string $type
     * @param EntityInterface $entity
     */
    public function processForm(string $type, EntityInterface $entity): void
    {
        $form = $this->formFactory->create($type, $entity, ['csrf_protection' => false]);
        $form->handleRequest($this->request);
        $this->checkForm($form);
    }
}

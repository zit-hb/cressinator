<?php

namespace App\Controller;

use App\Entity\SourceEntity;
use App\Form\SourceType;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SourceController extends AbstractController
{
    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/sources/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $source = new SourceEntity();
        $form->processForm(SourceType::class, $source);

        $em = $this->getDoctrine()->getManager();
        $em->persist($source);
        $em->flush();

        return new JsonResponse($serializer->normalize($source));
    }
}

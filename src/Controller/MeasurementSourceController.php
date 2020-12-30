<?php

namespace App\Controller;

use App\Entity\MeasurementSourceEntity;
use App\Form\MeasurementSourceType;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeasurementSourceController extends AbstractController
{
    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/measurement_sources/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $source = new MeasurementSourceEntity();
        $form->processForm(MeasurementSourceType::class, $source);

        $em = $this->getDoctrine()->getManager();
        $em->persist($source);
        $em->flush();

        return new JsonResponse($serializer->normalize($source));
    }
}

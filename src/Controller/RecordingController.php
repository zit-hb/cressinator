<?php

namespace App\Controller;

use App\Entity\RecordingEntity;
use App\Form\RecordingType;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecordingController extends AbstractController
{
    /**
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/recordings/add")
     */
    public function add(Request $request, SerializeService $serializer): Response
    {
        $recording = new RecordingEntity();
        $form = $this->createForm(RecordingType::class, $recording, ['csrf_protection' => false]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new BadRequestHttpException('Form not submitted');
        }

        if (!$form->isValid()) {
            throw new BadRequestHttpException('Form not valid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($recording);
        $em->flush();

        return new JsonResponse($serializer->normalize($recording));
    }
}

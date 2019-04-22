<?php

namespace App\Controller;

use App\Entity\SourceEntity;
use App\Form\SourceType;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SourceController extends AbstractController
{
    /**
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/sources/add")
     */
    public function add(Request $request, SerializeService $serializer): Response
    {
        $source = new SourceEntity();
        $form = $this->createForm(SourceType::class, $source, ['csrf_protection' => false]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new BadRequestHttpException('Form not submitted');
        }

        if (!$form->isValid()) {
            throw new BadRequestHttpException('Form not valid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($source);
        $em->flush();

        return new JsonResponse($serializer->normalize($source));
    }
}

<?php

namespace App\Controller;

use App\Entity\GroupEntity;
use App\Form\GroupType;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupController extends AbstractController
{
    /**
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/groups/add")
     */
    public function add(Request $request, SerializeService $serializer): Response
    {
        $group = new GroupEntity();
        $form = $this->createForm(GroupType::class, $group, ['csrf_protection' => false]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new BadRequestHttpException('Form not submitted');
        }

        if (!$form->isValid()) {
            throw new BadRequestHttpException('Form not valid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->normalize($group));
    }
}

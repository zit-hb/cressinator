<?php

namespace App\Controller;

use App\Entity\GroupEntity;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Service\SerializeService;
use App\Traits\FormControllerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupController extends AbstractController
{
    use FormControllerTrait;

    /**
     * @return Response
     * @Route("/groups", name="groups_list")
     */
    public function list(): Response
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->getDoctrine()->getRepository(GroupEntity::class);
        $groups = $groupRepository->findAll();
        return $this->render('group/list.html.twig', ['groups' => $groups]);
    }

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
        $this->checkForm($form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->normalize($group));
    }
}

<?php

namespace App\Controller;

use App\Entity\GroupEntity;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupController extends AbstractController
{
    /**
     * @return Response
     * @Route("/groups", name="groups_list")
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $em->createQuery("SELECT a FROM App:GroupEntity a");
        $groups = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('group/list.html.twig', ['groups' => $groups]);
    }

    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/groups/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $group = new GroupEntity();
        $form->processForm(GroupType::class, $group);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->normalize($group));
    }
}

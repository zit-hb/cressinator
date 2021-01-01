<?php

namespace App\Controller;

use DateTime;
use App\Entity\GroupEntity;
use App\Entity\MeasurementEntity;
use App\Entity\MeasurementSourceEntity;
use App\Form\MeasurementType;
use App\Repository\MeasurementSourceRepository;
use App\Repository\GroupRepository;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeasurementController extends AbstractController
{
    /**
     * @param string $groupId
     * @return Response
     * @Route("/measurements/group:{groupId}", name="measurement_by_group")
     */
    public function showByGroup(string $groupId): Response
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->getDoctrine()->getRepository(GroupEntity::class);
        $group = $groupRepository->find($groupId);

        return $this->render('measurement/group.html.twig', [
            'group' => $group
        ]);
    }

    /**
     * @param string $sourceId
     * @return Response
     * @Route("/measurements/source:{sourceId}", name="measurement_by_source")
     */
    public function showBySource(string $sourceId): Response
    {
        /** @var MeasurementSourceRepository $measurementSourceRepository */
        $measurementSourceRepository = $this->getDoctrine()->getRepository(MeasurementSourceEntity::class);
        $source = $measurementSourceRepository->find($sourceId);

        return $this->render('measurement/source.html.twig', [
            'source' => $source,
            'group' => $source->getGroup()
        ]);
    }

    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/measurements/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $measurement = new MeasurementEntity();
        $form->processForm(MeasurementType::class, $measurement);

        $source = $measurement->getSource();
        $source->setUpdatedAt(new DateTime());
        $group = $source->getGroup();
        $group->setUpdatedAt(new DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($measurement);
        $em->persist($source);
        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->normalize($measurement));
    }
}

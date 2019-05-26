<?php

namespace App\Controller;

use App\Entity\MeasurementEntity;
use App\Entity\SourceEntity;
use App\Form\MeasurementType;
use App\Repository\SourceRepository;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeasurementController extends AbstractController
{
    /**
     * @param string $group
     * @return Response
     * @Route("/measurements/group:{group}", name="measurement_by_group")
     */
    public function showByGroup(string $group): Response
    {
        /** @var SourceRepository $sourceRepository */
        $sourceRepository = $this->getDoctrine()->getRepository(SourceEntity::class);
        $sources = $sourceRepository->findByGroup($group);
        return $this->render('measurement/group.html.twig', [
            'sources' => $sources,
            'group' => $group
        ]);
    }

    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/measurements/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $measurement = new MeasurementEntity();
        $form->processForm(MeasurementType::class, $measurement);

        $em = $this->getDoctrine()->getManager();
        $em->persist($measurement);
        $em->flush();

        return new JsonResponse($serializer->normalize($measurement));
    }
}

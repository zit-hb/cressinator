<?php

namespace App\Controller;

use App\Entity\MeasurementEntity;
use App\Entity\SourceEntity;
use App\Form\MeasurementType;
use App\Service\SerializeService;
use App\Repository\SourceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeasurementController extends AbstractController
{
    /**
     * @param string $group
     * @return Response
     * @Route("/measurements/group:{group}")
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
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/measurements/add")
     */
    public function add(Request $request, SerializeService $serializer): Response
    {
        $measurement = new MeasurementEntity();
        $form = $this->createForm(MeasurementType::class, $measurement, ['csrf_protection' => false]);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new BadRequestHttpException('Form not submitted');
        }

        if (!$form->isValid()) {
            throw new BadRequestHttpException('Form not valid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($measurement);
        $em->flush();

        return new JsonResponse($serializer->normalize($measurement));
    }
}

<?php

namespace App\Controller;

use App\Entity\RecordingSourceEntity;
use App\Form\RecordingSourceType;
use App\Repository\RecordingSourceRepository;
use App\Service\Api\FormService;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecordingSourceController extends AbstractController
{
    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/recording_sources/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $source = new RecordingSourceEntity();
        $form->processForm(RecordingSourceType::class, $source);

        $em = $this->getDoctrine()->getManager();
        $em->persist($source);
        $em->flush();

        return new JsonResponse($serializer->normalize($source));
    }

    /**
     * @param string $groupId
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/recording_sources/group:{groupId}")
     */
    public function listByGroup(string $groupId, SerializeService $serializer): JsonResponse
    {
        /** @var RecordingSourceRepository $em */
        $recordingSourceRepository = $this->getDoctrine()->getRepository(RecordingSourceEntity::class);
        /** @var RecordingSourceEntity[] $sources */
        $sources = $recordingSourceRepository->findBy(['group' => $groupId]);

        return new JsonResponse($serializer->normalize($sources));
    }
}

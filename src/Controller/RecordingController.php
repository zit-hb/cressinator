<?php

namespace App\Controller;

use App\Service\Api\FormService;
use DateTime;
use App\Entity\RecordingEntity;
use App\Form\RecordingType;
use App\Repository\RecordingRepository;
use App\Service\SerializeService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RecordingController extends AbstractController
{
    /**
     * @param string $id
     * @return Response
     * @Route("/recordings/show:{id}")
     */
    public function showById(string $id)
    {
        /** @var RecordingRepository $recordingRepository */
        $recordingRepository = $this->getDoctrine()->getRepository(RecordingEntity::class);
        /** @var ?RecordingEntity $recording */
        $recording = $recordingRepository->find($id);

        if (!$recording) {
            throw new NotFoundHttpException('Id not found');
        }

        return new BinaryFileResponse($this->getParameter('upload_directory') . '/' . $recording->getFile());
    }

    /**
     * @param string $date
     * @param string $group
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/recordings/closest:{date}/group:{group}")
     */
    public function closestByGroup(string $date, string $group, Request $request, SerializeService $serializer): Response
    {
        /** @var RecordingRepository $recordingRepository */
        $recordingRepository = $this->getDoctrine()->getRepository(RecordingEntity::class);
        $recording = $recordingRepository->findClosestByGroup(new DateTime($date), $group);
        return new JsonResponse($serializer->normalize($recording));
    }

    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/recordings/add")
     */
    public function add(FormService $form, SerializeService $serializer): JsonResponse
    {
        $recording = new RecordingEntity();
        $form->processForm(RecordingType::class, $recording);

        /** @var UploadedFile $file */
        $file = $recording->getFile();
        $fileName = time() . '_' . hash('sha256', random_bytes(16));
        $file->move($this->getParameter('upload_directory'), $fileName);
        $recording->setFile($fileName);
        $recording->setFileName($file->getClientOriginalName());

        $em = $this->getDoctrine()->getManager();
        $em->persist($recording);
        $em->flush();

        return new JsonResponse($serializer->normalize($recording));
    }
}

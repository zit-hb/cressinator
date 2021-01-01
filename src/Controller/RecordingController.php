<?php

namespace App\Controller;

use DateTime;
use App\Service\Api\FormService;
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
    public function showById(string $id): BinaryFileResponse
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
     * @param string $groupId
     * @param Request $request
     * @param SerializeService $serializer
     * @return Response
     * @Route("/api/recordings/closest:{date}/group:{groupId}")
     */
    public function closestByGroup(string $date, string $groupId, Request $request, SerializeService $serializer): Response
    {
        /** @var RecordingRepository $recordingRepository */
        $recordingRepository = $this->getDoctrine()->getRepository(RecordingEntity::class);
        $recordings = $recordingRepository->findClosestByGroup(new DateTime($date), $groupId);
        return new JsonResponse($serializer->normalize($recordings));
    }

    /**
     * @param FormService $form
     * @param SerializeService $serializer
     * @return JsonResponse
     * @Route("/api/recordings/add")
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

        $source = $recording->getSource();
        $source->setUpdatedAt(new DateTime());
        $group = $source->getGroup();
        $group->setUpdatedAt(new DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($recording);
        $em->persist($source);
        $em->persist($group);
        $em->flush();

        return new JsonResponse($serializer->normalize($recording));
    }
}

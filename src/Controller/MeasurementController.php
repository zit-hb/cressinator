<?php

namespace App\Controller;

use App\Entity\SourceEntity;
use App\Repository\SourceRepository;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('measurement/group.html.twig', ['sources' => $sources]);
    }
}

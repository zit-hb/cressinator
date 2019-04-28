<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RootController extends AbstractController
{
    /**
     * @return Response
     * @Route("/", name="root")
     */
    public function root(): Response
    {
        return $this->render('root/index.html.twig');
    }
}

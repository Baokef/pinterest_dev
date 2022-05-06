<?php

namespace App\Controller;

use App\Entity\Pin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $pin = new Pin;
        dump($pin);
        //die;
        return $this->render('pin/index.html.twig', [
            'controller_name' => 'PinController',
        ]);
        //return new Response("Salut Bao");
    }
}

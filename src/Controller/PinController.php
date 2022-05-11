<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repo): Response
    {
        $pin1 = new Pin;
        $pin2 = new pin;
        $pin3 = new Pin;
        $pin4 = new pin;
        $pin5 = new Pin;

        $pin1->setTitle("Pin 1");
        $pin1->setDescription("Description Pin 1");
        $pin2->setTitle("Pin 2");
        $pin2->setDescription("Description Pin 2");
        $pin3->setTitle("Pin 3");
        $pin3->setDescription("Description Pin 3");
        $pin4->setTitle("Pin 4");
        $pin4->setDescription("Description Pin 4");
        $pin5->setTitle("Pin 5");
        $pin5->setDescription("Description Pin 5");

        $em = $this->getDoctrine()->getManager();
        $em->persist($pin1);
        $em->persist($pin2);
        $em->persist($pin3);
        $em->persist($pin4);
        $em->persist($pin5);


        $em->flush();

        return $this->render('pin/index.html.twig', ['pins' => $repo->findAll()]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PinType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class PinController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repo): Response
    {


        if ($this->getUser()) {
            if (!$this->getUser()->isVerified()) {
                $this->addFlash('info', 'Your email address is not verified.');
            }
        }



        
        return $this->render('pin/index.html.twig', ['pins' => $repo->findAll()]);
    }
   
    /**
     * @Route("/pin/{id<[0-9]+>}", name="app_pin_show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', compact('pin'));
    }
    /**
     * @Route("/pin/create", name="app_pin_create", methods= {"GET","POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'You must confirm your email to create Pin!');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', 'You must login to create Pin!');
            return $this->redirectToRoute('app_login');
        }

        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();
            $this->addFlash('success', 'pin successfully created !');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pin/create.html.twig', ['monForm' => $form->createView()]);
    }
    /**
     * @Route("/pin/{id<[0-9]+>}/edit", name="app_pin_edit", methods={"GET","POST"})
     */

    public function edit(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'You must confirm your email to edit Pin!');
                return $this->redirectToRoute('app_home');
            } else if ($pin->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('error', 'You must to be the user ' . $pin->getUser()->getFirstname() . ' to edit Pin !');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', 'You must login to edit Pin!');
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'pin successfully updated !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pin/edit.html.twig', [
            'pin' => $pin,
            'monForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/pin/{id<[0-9]+>}/delete", name="app_pin_delete")
     */
    public function delete(Pin $pin, EntityManagerInterface $em): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('error', 'You must confirm your email to delete Pin!');
                return $this->redirectToRoute('app_home');
            } else if ($pin->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('error', 'You must to be the user ' . $pin->getUser()->getFirstname() . ' to delete this Pin !');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', 'You must login to delete this Pin!');
            return $this->redirectToRoute('app_login');
        }
        $em->remove($pin);
        $em->flush();
        $this->addFlash('info', 'pin successfully deleted !');
        return $this->redirectToRoute('app_home');
    }
}

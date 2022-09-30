<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Pin;
use App\Repository\PinRepository;
use App\Form\AccountType;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function show(PinRepository $repo): Response
    {   if($this->getUser()==false){
        $this->addFlash('error', 'tu dois te logger pour voir le compte');
        return $this->redirectToRoute('app_login');
        }
        return $this->render('account/show.html.twig', ['pins' => $repo->findAll()]);
    }
    /**
     * @Route("/account/edit", name="app_account_edit")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        if ($this->getUser() == false) {
            $this->addFlash('error', 'tu dois te logger pour voir le compte');
            return $this->redirectToRoute('app_login');
        }
            
        $form = $this->createForm(AccountType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'account successfully updated !');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'account' => $this->getUser(),
            'monForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/account", name="app_account_delete")
     */
    public function delete( EntityManagerInterface $em): Response
    {
        
    
            $this->addFlash('error', 'You must login to delete this Account!');
            return $this->redirectToRoute('app_login');
        
        $em->remove($this->getUser());
        $em->flush();
        $this->addFlash('info', 'account successfully deleted !');
        return $this->redirectToRoute('app_home');
    }
}

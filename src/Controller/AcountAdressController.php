<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adresse;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcountAdressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    #[Route('/compte/adresses', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/adress.html.twig');
    }


    //Ajout adresse
    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]
    public function add(Cart $cart,Request $request): Response
    {
        $addresse = new Adresse();
        $form = $this->createForm(AddressType::class, $addresse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //permet d'ajouter l'id de l'utilisateur dans l'adresse
            $addresse->setUser($this->getUser());

            $this->entityManager->persist($addresse);
            $this->entityManager->flush();

            if($cart->get())
            {
                return $this->redirectToRoute('order');
            }
            return $this->redirectToRoute('account_address');

        }

        return $this->render('account/address_add.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    //Modifier une adresse
    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')]
    public function edit(Request $request, $id): Response
    {
        //On récupère l'adresse en fonction de l'id
        $addresse = $this->entityManager->getRepository(Adresse::class)->findOneById($id);

        //On test si l'adresse existe, on vérifie ensuite si l'id de l'adresse dans adresse est différent de l'id de la personne connecté
        if(!$addresse || $addresse->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $addresse);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');

        }

        return $this->render('account/address_add.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    //Suppression d'une adresse
    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_address_delete')]
    public function delete($id): Response
    {
        $addresse = $this->entityManager->getRepository(Adresse::class)->findOneById($id);

        if($addresse && $addresse->getUser() == $this->getUser())
        {
            //permet de supprimer une ligne de la bdd
           $this->entityManager->remove($addresse);
           $this->entityManager->flush();
        }

        return $this->render('account/adress.html.twig');
    }
}

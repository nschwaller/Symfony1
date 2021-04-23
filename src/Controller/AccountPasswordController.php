<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/compte/modifier_mdp", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification=null;

        //Récupère les informations de l'utilisateur connecté
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Récupéré la date que l'utilisateur a mis dans l'impur old_password
            $old_pwd = $form->get('old_password')->getData();

            //Fonction qui permet d'encoder le mot de passe et de tester si le mot de passe marqué est valide
            if($encoder->isPasswordValid($user, $old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                //permet d'encoder le nouveau mot de passe
                $password = $encoder->encodePassword($user, $new_pwd);

                //Les informations du formulaire sont automatiquement set mais comme ici on change le mot de passe on doit set le nouveau mot de passe pour l'enregistrer
                $user->setPassword($password);
                $this->entityManager -> persist($user);
                $this->entityManager -> flush();

                $notification = "Votre mot de passe a bien été mis à jour";
            } else {
                $notification ="Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}

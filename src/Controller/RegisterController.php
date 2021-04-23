<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    //Permet de faire les manipulations qui permettent de réucpéré les information et les envoyer dans la bdd
    private $entityManager;

    //Constructeur avec entityManager
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        //UserpasswordEncoderInterface va nous permettre de crypter notre mdp

        // il faut instancier la classe user car le formulaire va enregistrer les données dans user
        //et va surtout prendre un formulaire qui se base sur user
        $user = new User();

        //instancier le formulaire
        $form = $this->createForm(RegisterType::class, $user);

        // il faut écouter si il y a quelque chose dans le formulaire
        $form-> handleRequest($request);

        // On vérifie si le formulaire a été soumis et si il est valide
        if($form -> isSubmitted() && $form->isValid()){

            //dans user tu dois mettre les informations du formulaire que l'utilisateur a rentré
            $user = $form->getData();
            //On va encoder le getpassword donc le mot de passe que l'utilisateur aura saisie ensuite on va remplacer le mot de passe de l'utilisateur par son mot de passe encodé
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);

            //persist permet de dire que l'objet doit être enregistré flush permet de mettre a jour la bdd avec les informations enregistré
            $this->entityManager -> persist($user);
            $this->entityManager -> flush();
            return $this->redirectToRoute('account');
        }

        return $this->render('register/index.html.twig', [
            //on envoie notre formulaire au template pour pouvoir l'utiliser avec twig
            'form'=>$form->createView()
        ]);
    }
}

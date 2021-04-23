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
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        // il faut instancier la classe user car le formulaire va enregistrer les données dans user
        //et va surtout prendre un formulaire qui se base sur user
        $user = new User();
        //instancier le formulaire
        $form = $this->createForm(RegisterType::class, $user);

        $form-> handleRequest($request);

        if($form -> isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $password = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);

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

<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    //Choix de l'adresse et du livreur
    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        //on test si l'utilisateur a déjà des adresses, s'il n'en a pas on le redirige vers le formulaire d'ajout d'adresse
        //getValues permet de savoir si il y a des valeur
        if(!$this->getUser()->getAdresses()->getValues())
        {
            return $this->redirectToRoute('account_address_add');
        }

        //Permet de passer un utilisateur en option
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig',[
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    //Recapitulatif de la commande
    #[Route('/commande/recapitulatif', name: 'order_recap')]
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //Récupère la date d'aujourd'hui
            $date = new \DateTime();


            //récupère sur quel bouton radio l'utilisateur a cliqué
            $carriers = $form->get('carriers')->getData();
            $adresse = $form->get('adresses')->getData();


            //Récupération de toutes les informations sur l'adresse choisis par l'utilisateur (on les met dans une seule variable car on écrit tout en dur dans la base de données au niveau de la commande
            $delivery_content = $adresse->getFirstname().' '.$adresse->getLastname();
            $delivery_content .= '<br/>'.$adresse->getPhone();
            if($adresse->getCompany())
            {
                $delivery_content .= '<br/>'.$adresse->getCompany();
            }
            $delivery_content .= '<br/>'.$adresse->getAddress();
            $delivery_content .= '<br/>'.$adresse->getPostal().' '.$adresse->getCity();
            $delivery_content .= '<br/>'.$adresse->getCountry();


            $order = new Order();
            //permet de récupèrer une date au format 21/02/2021
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            //Configuration du transpoirteur marqué en dr (non lié par une relation)
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            //La commande n'est pas payé
            $order->setIsPaid(0);

            //On persist déjà order car orderdetails a besoin d'order
            $this->entityManager->persist($order);


            //enregistrer les produits du panier dans la commande
            foreach ($cart->getFull() as $product){

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();
            //dd ($cart->getFull());

            //L'affichage de la vue se fait uniquement quand le formulaire du choix est valider, pour eviter que qq'un marque tout de suite l'url
            return $this->render('order/recap.html.twig',[
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference'=> $order->getReference()
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}

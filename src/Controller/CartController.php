<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }



    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {

        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }


    //Fonction d'ajout d'un produit dans le panier
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    //Instantiation d'un objet panier
    public function add(Cart $cart, $id): Response
    {
        //appel de la fonction add dans la clasee cart
        $cart->add($id);

        //Quand un produit est ajouté au panier, on redirige le client vers son panie
        return $this->redirectToRoute('cart');
    }


    //Suppression du panier
    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('product');
    }


    //Suppresion d'un seul produit du panier
    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }


    //Permet de retirer une quantité
    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}

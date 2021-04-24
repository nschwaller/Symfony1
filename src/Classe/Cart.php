<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{

    //Classe crée à la main
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,SessionInterface $session)
    {
        $this->session=$session;
        $this->entityManager=$entityManager;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        //On test si on a déjà ajouté le produit alors on mais cart a plus 1 sinon on lui donn la valeur 1
        //l'affichage sera id ==> valeur (1/2/3/etc..)
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
        }else
        {
            $cart[$id]=1;
        }

        $this->session->set('cart', $cart);
    }

    //retourne tout ce qui se trouve dans la session cart
    public function get()
    {
        return $this->session->get('cart');
    }

    //Suppresion de la session cart
    public  function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        //permet de retirer du tableau cart un objet, ici on retire un id donc un produit
        unset($cart[$id]);

        return  $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        //On test si la quantité d'id d'un produit est supérieur a un, si oui on enleve une quantité, sinon on supprime le produit du panier
        if($cart[$id]>1)
        {
            $cart[$id]--;
        }else
        {
            unset($cart[$id]);
        }

        return  $this->session->set('cart', $cart);
    }


    public function getFull()
    {
        $cartComplete =[];

        //si il y a des information dans mon panier
        if($this->get()){

            //On parcours la totalité du panier (get l'instance actuel donc panier) on donne des clé, id vers quantité
            foreach ($this->get() as $id => $quantity){

                //On récupère les informarmations du produit grâce à l'id dans le panier
                $product_object = $this->entityManager->getRepository(Product::class )->findOneById($id);

                //on test si l'objet existe ou non (par exemple si l'utilisateur rentre un id au hasard, donc on supprime cette fausse id
                if(!$product_object){
                    $this->delete($id);
                    //correspond à un exit (on sort de la boucle et on retourne en haut du foreach
                    continue;
                }

                //on stock nos informations dans $carCOmplete qui contiendra toutes les informations d'un produit et sa quantité
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }
}







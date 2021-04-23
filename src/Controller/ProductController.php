<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager=$entityManager;
    }

    #[Route('/nos-produits', name: 'product')]
    public function index(Request $request): Response
    {

        
        $search =new Search();    
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $search = $form->getData();
            //récupéré tout les produits en fonction de la recherche findwithsearch a été crée à la main
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }
        else
        {
            //Permet de récupéré tout les produits de la base de données
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig',[
            'products' => $products,
            'form'=>$form->createView()
        ]);
    }

    //Paramètre qui va nous permettre d'afficher le produit en fonction de son nom c'est comme si on affiichait l'id du produit mais c'est plus sécurisé car c'est son slug
    #[Route('/produit/{slug}', name: 'products')]
    //on récupère le paramètre de notre route
    public function show($slug): Response
    {
        //Récupère dans la base de données le prduit qui a le slug equivalent au paramètre slug (select * from product where slug = {slug})
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        //si le produit est pas trouvé par exemple qu'un utilisateur marque n'importe quoi, on redirige vers tout les produits
        if(!$product){
            //équivalent d'un header
            return $this->redirectToRoute('product');
        }
        return $this->render('product/show.html.twig',[
            'product' => $product
        ]);
    }
}

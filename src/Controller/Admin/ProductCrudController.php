<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    //permet de modifier les imputs lorsque l'on créer un nouveau produits dans notre cas
    public function configureFields(string $pageName): iterable
    {
        return [
            //on souhaite avoir un imput texte
            TextField::new('name'),
            //Permet d'avoir un input type slug, avec ce slug on récupere le nom au dessus (setTargetFieldName pour que symfony sache ce qu'il doit mettre au format slug
            SlugField::new('slug')->setTargetFieldName('name'),
            //Imagefield permet de séléctionné un image dans nos dossiers, setbasePath permet de définir ou se situe nos images, le deuxième parametre est obligatoire cette fois il faut mettre le chemin complet avec public, ensuite on doit encoder l'image, on le fais donc aléatoirement .extension permet de récupéré l'extension du fichier
            ImageField::new('illustration')->setBasePath('uploads/')->setUploadDir('public/uploads')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
            TextField::new('subtitle'),
            //Permet d'avoir un imput type textarea
            TextareaField::new('description'),
            //Permet de ne marqué qu'un prix et de définir l'argent qu'on utilise
            MoneyField::new('price')->setCurrency('EUR'),
            //Permet d'afficher toutes les catégory pour pouvoir en lié une
            AssociationField::new('category')
        ];
    }

}

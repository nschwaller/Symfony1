<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    //permet d'afficher une nouvelle action (supprimer/editer etc...)
    public function configureActions(Actions $actions): Actions
    {
        //Action qui permet de voir
        return $actions
            ->add('index','detail');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            //Affichage d'un id
            IdField::new('id'),
            //Affichage d'une date avec modification du label
            DateTimeField::new('createdAt', 'Passée le')->setFormat('dd-MM-yyyy hh:mm:ss'),
            //Affichage d'un texte
            TextField::new('user.getFullName', 'Utilisateur'),
            MoneyField::new('total')->setCurrency('EUR'),
            //Affichage d'un bouton
            BooleanField::new('isPaid', 'Payée')
        ];
    }

}

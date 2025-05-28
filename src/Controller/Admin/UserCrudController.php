<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username')->setRequired(true),
            TextField::new('email')->setRequired(true),
            TextField::new('password', 'Hashed Password')->setRequired(true)->onlyOnForms(),
            AssociationField::new('articles' , 'Nombre de articles')->setFormTypeOptions(['by_reference' => false,])->hideOnForm(),
        ];
    }
}

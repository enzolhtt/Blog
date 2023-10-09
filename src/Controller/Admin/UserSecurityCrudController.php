<?php

namespace App\Controller\Admin;

use App\Entity\UserSecurity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserSecurityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserSecurity::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}

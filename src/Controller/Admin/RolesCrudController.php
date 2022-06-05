<?php

namespace App\Controller\Admin;

use App\Entity\Roles;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RolesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Roles::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Список ролей')
            ->setPageTitle('edit', 'Редактирование роли')
            ->setPageTitle('new', 'Создать роль')
            ->setDefaultSort(['name' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Название роли');
        yield TextField::new('roles_alt', 'Идентификатор роли');
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Создать новую роль');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить и продолжить редактирование');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить изменения и вернуться к списку');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить добавить следующую роль');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить изменения и вернуться к списку');
            });
    }
}

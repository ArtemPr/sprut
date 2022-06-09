<?php

namespace App\Controller\Admin;

use App\Entity\Roles;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private ManagerRegistry $doctrine)
    {

    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Список администраторов')
            ->setPageTitle('edit', 'Редактирование администратора')
            ->setPageTitle('new', 'Создать администратора')
            /*->setDefaultSort(['name' => 'DESC'])*/
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addTab('Основное');
        yield BooleanField::new('activity', 'Активность');

        yield TextField::new('surname', 'Фамилия');
        yield TextField::new('username', 'Имя');
        yield TextField::new('patronymic', 'Отчество');

        yield TextField::new('city', 'Город')->hideOnIndex();
        yield TelephoneField::new('phone', 'Телефон')->hideOnIndex();
        yield TextField::new('skype', 'Skype')->hideOnIndex();

        yield TextField::new('email', 'E-mail администратора');
        yield TextField::new('passwordNew', 'Новый пароль')
            ->hideOnIndex()
            ->setHelp('Для смены пароля либо оставьте пустым');

        yield ChoiceField::new('roles', 'Привилегии')
            ->allowMultipleChoices()
            ->autocomplete()
            ->setChoices(
                $this->setRoles()
            );

        yield TextField::new('api_hash', 'API ключ')->setDisabled()->hideOnIndex();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->set(Crud::PAGE_INDEX, Action::DELETE)
            ->setPermission('delete', 'ROLE_SUPERADMIN')
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel(' ');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pencil-square-o')->setLabel(' ');
            })
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Создать нового пользователя');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить и продолжить редактирование');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить изменения и вернуться к списку');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить добавить следующего пользователя');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setIcon('fa fa-check')->setLabel('Сохранить изменения и вернуться к списку');
            });
    }

    private function setRoles()
    {
        $x = $this->doctrine->getRepository(Roles::class)->findBy([], ['name' => 'ASC']);
        foreach ($x as $value) {
            $type_list[$value->getName()] = $value->getRolesAlt();
        }
        return $type_list;
    }
}

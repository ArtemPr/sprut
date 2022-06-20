<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\City;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    #[Route('/form/edit_user/{id}', name: 'edit_user')]
    public function getUserForm($id)
    {
        $result = $this->managerRegistry->getRepository(User::class)->getUser((int) $id);
        $city = $this->managerRegistry
            ->getRepository(City::class)
            ->createQueryBuilder('city')
            ->orderBy('city.name', 'DESC')
            ->getQuery()
            ->getArrayResult();

        return $this->render(
            'administrator/user/form/update_form.html.twig',
            [
                'data' => $result[0] ?? false,
                'city_list' => $city ?? false,
            ]
        );
    }
}

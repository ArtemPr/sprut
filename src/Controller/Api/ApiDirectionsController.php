<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Directions;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiDirectionsController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    use LoggerService;
    use ApiService;

    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $name = $this->doctrine->getRepository(Directions::class)->findOneBy(['direction_name' => trim($data['direction_name'])]);
        if (!empty($name)) {
            return $this->json(['result' => 'error']);
        }
        $directions = new Directions();
        $directions->setDirectionName(trim($data['direction_name']));
        $directions->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($directions);
        $entityManager->flush();
        $lastId = $directions->getId();
        $this->logAction('add_directions', 'Направления',
            'Добавлено направление id:'.$lastId.' name:'.$data['direction_name']);
        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $directions = $this->doctrine->getRepository(Directions::class)->find((int) $data['id']);
        $directions->setDirectionName(trim($data['direction_name']));
        $directions->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($directions);
        $entityManager->flush();
        $this->logAction('update_directions', 'Направления',
            'Обновление направления id:'.$data['id'].' name:'.$data['direction_name']);
        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide($id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $directions = $this->doctrine->getRepository(Directions::class)->find((int) $id);
        $directions->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($directions);
        $entityManager->flush();
        $data = $this->doctrine->getRepository(Directions::class)->find((int) $id);
        $this->logAction('delete_directions', 'Направления',
            'Удаление направления id:'.$data->getId().' name:'.$data->getDirectionName());
        return $this->json(['result' => 'success', 'id' => $id]);
    }
}

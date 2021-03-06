<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Kaferda;
use App\Entity\Loger;
use App\Entity\ProductLine;
use App\Entity\TrainingCenters;
use App\Entity\User;
use App\Repository\KaferdaRepository;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiKafedra extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private readonly KaferdaRepository $kaferdaRepository,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        if (!empty($data['training_centre'])) {
            $tc = $this->doctrine->getRepository(TrainingCenters::class)->find($data['training_centre']);
        } else {
            $tc = null;
        }

        if (!empty($data['director'])) {
            $dir = $this->doctrine->getRepository(User::class)->find($data['director']);
        } else {
            $dir = null;
        }

        $productLine = null;
        if (!empty($data['product_line'])) {
            $productLine = $this->doctrine->getRepository(ProductLine::class)->find($data['product_line']);
        }

        $parent = $this->doctrine->getRepository(Kaferda::class)->find((int) $data['parent_id']);

        $kafedra = new Kaferda();
        $kafedra->setId((int) $data['id']);
        $kafedra->setName(trim($data['name']));
        $kafedra->setTrainingCentre($tc);
        $kafedra->setDirector($dir);
        $kafedra->setDelete(false);
        $kafedra->setProductLine($productLine);
        $kafedra->setParent($parent);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();
        $lastId = $kafedra->getId();

        $this->logAction('add_kafedra', '??????????????', '???????????????????? ?????????????? ' . $lastId . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        if (!empty($data['training_centre'])) {
            $tc = $this->doctrine->getRepository(TrainingCenters::class)->find((int) $data['training_centre']);
        } else {
            $tc = null;
        }

        if (!empty($data['director'])) {
            $dir = $this->doctrine->getRepository(User::class)->find($data['director']);
        } else {
            $dir = null;
        }

        $productLine = null;
        if (!empty($data['product_line'])) {
            $productLine = $this->doctrine->getRepository(ProductLine::class)->find($data['product_line']);
        }

        $parent = $this->doctrine->getRepository(Kaferda::class)->find((int) $data['parent_id']);

        $kafedra = $this->doctrine->getRepository(Kaferda::class)->find((int) $data['id']);
        $kafedra->setName(trim($data['name']));
        $kafedra->setTrainingCentre($tc);
        $kafedra->setDirector($dir);
        $kafedra->setDelete(false);
        $kafedra->setProductLine($productLine);
        $kafedra->setParent($parent);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();

        $this->logAction('update_kafedra', '??????????????', $data['id'] . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide($id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $kafedra = $this->doctrine->getRepository(Kaferda::class)->find((int) $id);
        $kafedra->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(Kaferda::class)->find((int)$id);

        $this->logAction('delete_kafedra', '??????????????', $id . ' ' . $data->getName());

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}

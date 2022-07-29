<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Kaferda;
use App\Entity\Loger;
use App\Entity\TrainingCenters;
use App\Repository\TrainingCentersRepository;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiTrainingCentre extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private readonly TrainingCentersRepository $trainingCentersRepository,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     */
    #[Route('/training_centre', name: 'api_get_training_centre_list', methods: ['GET'])]
    public function getTrainingCentreList(ManagerRegistry $doctrine): Response
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_training_centre_list')) {
            return $this->json(['error' => 'error auth']);
        }

        $result = $this->trainingCentersRepository->findAll();

        foreach ($result as $val) {
            $url = $val->getUrl();
            $url = str_replace(['http:','https:','/'],'', $url);
            $out[] = [
                'centre_id' => $val->getId(),
                'centre_name' => \mb_convert_encoding($val->getName(), 'utf8'),
                'url' => $url,
            ];
        }

        return $this->json($out ?? ['error' => 'no results']);
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @TODO сделать выборку в репозитории
     */
    #[Route('/training_centre/{id}', name: 'api_get_training_centre', methods: ['GET'])]
    public function getTrainingCentre($id)
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_training_centre')) {
            return $this->json(['error' => 'error auth']);
        }

        $result = $this->trainingCentersRepository->find($id);

        return $this->json($result ?? []);
    }



    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = new TrainingCenters();
        $tc->setName(trim($data['name']));
        $tc->setUrl($data['url'] ?? null);
        $tc->setEmail($data['email'] ?? null);
        $tc->setPhone($data['phone'] ?? null);
        $tc->setExternalUploadSdoId($data['external_upload_sdo_id'] ?? null);
        $tc->setExternalUploadBakalavrmagistrId($data['external_upload_bakalavrmagistr_id'] ?? null);
        $tc->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();
        $lastId = $tc->getId();

        $this->logAction('add_tc', 'Учебные центры', $lastId . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id'=>$lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = $this->doctrine->getRepository(TrainingCenters::class)->find((int)$data['id']);
        $tc->setName(trim($data['name']));
        $tc->setUrl($data['url'] ?? null);
        $tc->setEmail($data['email'] ?? null);
        $tc->setPhone($data['phone'] ?? null);
        $tc->setExternalUploadSdoId($data['external_upload_sdo_id'] ?? null);
        $tc->setExternalUploadBakalavrmagistrId($data['external_upload_bakalavrmagistr_id'] ?? null);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();

        $this->logAction('update_tc', 'Учебные центры', $data['id'] . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id'=>$data['id']]);
    }

    public function hide($id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $kafedra = $this->doctrine->getRepository(TrainingCenters::class)->find((int)$id);
        $kafedra->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(Kaferda::class)->find((int)$id);

        $this->logAction('delete_tc', 'Учебные центры', $id . ' ' . $data->getName());

        return $this->json(['result' => 'success', 'id'=>$id]);

    }
}

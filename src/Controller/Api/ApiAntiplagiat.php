<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Entity\Loger;
use App\Repository\AntiplagiatRepository;
use App\Service\ApiService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiAntiplagiat extends AbstractController
{
    use ApiService;
    use UploadedFilesService;

    protected $checkedFile;

    public function __construct(
        private readonly AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
//        $files = $request->files->all();

        $discipline = null;

        if (!empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        $fileUploadedPath = $this->uploadFile('file', Antiplagiat::class);

//        dd([
//            '$this->checkedFile' => $this->checkedFile,
//        ]);

        $antiplagiat = new Antiplagiat();
        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);
        $antiplagiat->setDataCreate(new \DateTime());
        $antiplagiat->setStatus(1);
        $antiplagiat->setFile($fileUploadedPath);
        $antiplagiat->setSize($this->checkedFile['filesize']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();
        $lastId = $antiplagiat->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_antiplagiat');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Антиплагиат');
        $loger->setComment('Создание запроса '.$lastId);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        //
        // файл не обновляем
        //
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $discipline = null;

        if (!empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

//        dd($this->json([
//            '$data' => $data,
//        ]));

        $antiplagiat = $this->doctrine->getRepository(Antiplagiat::class)->find($data['id']);

        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_antiplagiat');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Антиплагиат');
        $loger->setComment('Обновление запроса '.$data['id']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}

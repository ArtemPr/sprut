<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Discipline;
use App\Entity\Litera;
use App\Repository\LiteraRepository;
use App\Service\ApiService;
use App\Service\Litera5API;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ApiLitera extends AbstractController
{
    use ApiService;
    use UploadedFilesService;
    use LoggerService;

    public function __construct(
        private readonly LiteraRepository $literaRepository,
        private readonly ManagerRegistry $doctrine,
        protected Litera5API $litera5API
    ) {
    }

    public function add(): JsonResponse
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $uploadedFilename = null;
        $discipline = null;
        $filename = null;

        if (empty($data['unique_discipline']) && !empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        if (!empty($data['doc_name'])) {
            $slugger = new AsciiSlugger();
            $filename = $slugger->slug($data['doc_name'])->lower()->truncate(128)->toString();
        }

        // если у нас есть $data['content'] и нет файла, то необходимо файл сделать, в противном случае используем файл
        if (empty($data['content']) && UPLOAD_ERR_OK != $_FILES['file']['error']) {
            return $this->json(['result' => 'error', 'error' => 'Или укажите файл, или укажите текст!']);
        }

        $uploadedFilename = !empty($data['content'])
            ? $this->uploadFileFromContent($data['content'], Litera::class, $filename)
            : $this->uploadFile('file', Litera::class);

        if (empty($uploadedFilename)) {
            // Вернуть тут ошибку, если файл не загрузился!
            return $this->json(['result' => 'error', 'error' => 'Файл не загружен!']);
        }

        $litera = new Litera();
        $litera
            ->setAuthor($this->getUser())
            ->setDiscipline($discipline)
            ->setDataCreate(new \DateTime())
            ->setFile($uploadedFilename)
            ->setSize($this->checkedFile['filesize'])
            ->setStatus(LiteraRepository::CHECK_STATUS_NEW)
            ->setDocName($data['doc_name'])
        ;

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($litera);
        $entityManager->flush();
        $lastId = $litera->getId();

        $this->logAction(
            'add_litera',
            'Литера5',
            'Создание запроса '.$lastId
        );

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): JsonResponse
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        dd([
            '$data' => $data ?? '-',
        ]);

        $this->logAction(
            'update_litera',
            'Литера5',
            'Создание запроса '.$lastId
        );

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}

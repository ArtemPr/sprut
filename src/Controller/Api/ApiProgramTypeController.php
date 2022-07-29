<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\ProgramType;
use App\Repository\ProgramTypeRepository;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiProgramTypeController extends AbstractController
{
    use LoggerService;

    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add() : Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $name = $this->managerRegistry->getRepository(ProgramType::class)->findOneBy(['name_type' => trim($data['name_type'])]);
        if (!empty($name)) {
            return $this->json(['result' => 'error']);
        }
        $program_type = new ProgramType();
        $program_type->setNameType(trim($data['name_type']));
        $program_type->setShortNameType(trim($data['short_name_type']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($program_type);
        $entityManager->flush();
        $lastId = $program_type->getId();

        $this->logAction('add_program_type', 'Тип программы', 'Обновление типов программ '.$lastId.' '.$data['name_type'].' '.$data['short_name_type']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $program_type = $this->doctrine->getRepository(ProgramType::class)->find((int) $data['id']);
        $program_type->setNameType(trim($data['name_type']));
        $program_type->setShortNameType(trim($data['short_name_type']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($program_type);
        $entityManager->flush();

        $this->logAction('update_program_type', 'Тип программы', $data['id'].' '.$data['name_type'].' '.$data['short_name_type']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}

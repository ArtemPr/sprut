<?php

namespace App\Controller;

use App\Entity\Literature;
use App\Entity\LiteratureCategory;
use App\Entity\MasterProgram;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeleportController extends AbstractController
{
    #[Route('/teleport/product', name: 'app_teleport')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $out = 0;

        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=programs');
        $data_item = json_decode($data_item);


        $entityManager = $doctrine->getManager();

        foreach ($data_item as $key => $val) {
            $program = $entityManager->getRepository(MasterProgram::class)->find($val->program_id);

            if (empty($program)) {
                $program = new MasterProgram();
            }

            $program->setName($val->name);
            $program->setId($val->program_id);
            $program->setLengthHour($val->hours_total);
            $program->setLengthWeek($val->weeks_total);
            $program->setProgramType($val->type);
            $program->setLengthWeekShort($val->weeks_short_total ?? 0);
            $entityManager->flush();

            if (empty($program)) {
                $entityManager->persist($program);
            }
        }

        return $this->render('teleport/index.html.twig', [
            'out' => 'Imported ' . $out . ' program.'
        ]);
    }

    #[Route('/teleport/books_sections', name: 'app_books_sections')]
    public function getLiteratureCategory(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=books_sections&amp;p=4');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
            $program = $entityManager->getRepository(LiteratureCategory::class)->find($val->books_section_id);
            if(empty($program)) {
                $literature = new LiteratureCategory();
                $literature->setName($val->name);
                $literature->setId($val->books_section_id);
                $entityManager->flush();
                $entityManager->persist($literature);
            }
        }
        return $this->render('teleport/index.html.twig', [
            'out' => 'Imported'
        ]);
    }

    #[Route('/teleport/books', name: 'app_books')]
    public function addLiterature(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=book');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
                $literature = new Literature();
                $literature->setName($val->name);
                $literature->setId($val->book_id);
                $literature->setType($val->type);
                $literature->setAuthors($val->authors);
                $literature->setDescription($val->description);
                $literature->setYear($val->year);
                $literature->setLink($val->url);
                $entityManager->flush();
                $entityManager->persist($literature);
        }
        return $this->render('teleport/index.html.twig', [
            'out' => 'Imported'
        ]);
    }
}

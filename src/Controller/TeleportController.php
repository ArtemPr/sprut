<?php

namespace App\Controller;

use App\Entity\Literature;
use App\Entity\LiteratureCategory;
use App\Entity\MasterProgram;
use App\Entity\ProgramType;
use App\Entity\TrainingCenters;
use App\Entity\TrainingCentersRequisites;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeleportController extends AbstractController
{
    #[Route('/teleport/product', name: 'app_teleport')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $on_page = 1000;

        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=programs');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if(!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if($key < $start || $key > $end) {
                continue;
            }
            $program = $entityManager->getRepository(MasterProgram::class)->find($val->program_id);

            $type = $entityManager->getRepository(ProgramType::class)->find($val->type);

            if (empty($program)) {
                $program = new MasterProgram();
            }

            $program->setName($val->name);
            $program->setId($val->program_id);
            $program->setLengthHour($val->hours_total);
            $program->setLengthWeek($val->weeks_total);
            $program->setProgramType($type);
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



    #[Route('/teleport/training_centre', name: 'app_tr')]
    public function addTrainingCentre(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=training_centers');
        $data_item = json_decode($data_item);


        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
            $literature = new TrainingCenters();
            $literature->setName(html_entity_decode($val->name));
            $literature->setId($val->training_center_id);
            $literature->setPhone($val->phone);
            $literature->setEmail($val->email);
            $literature->setUrl($val->url);
            $literature->setExternalUploadBakalavrmagistrId((string)$val->external_upload_bakalavrmagistr_id);
            $literature->setExternalUploadSdoId((string)$val->external_upload_sdo_id);
            $entityManager->flush();
            $entityManager->persist($literature);
        }
        return $this->render('teleport/index.html.twig', [
            'out' => 'Imported'
        ]);
    }

    #[Route('/teleport/training_centre_rq', name: 'app_tr_rq')]
    public function getTrainingCentreRq(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=training_center_organizations');
        $data_item = json_decode($data_item);


        $entityManager = $doctrine->getManager();


        foreach ($data_item as $val) {

            $tc = $entityManager->getRepository(TrainingCenters::class)->find($val->training_center_id);

            $literature = new TrainingCentersRequisites();
            $literature->setId($val->training_center_organization_id);
            $literature->setTrainingCentre($tc);
            $literature->setDirector($val->director);
            $literature->setCity($val->city);
            $literature->setShortName(html_entity_decode($val->short_name));
            $literature->setAddress(html_entity_decode($val->address));
            $literature->setDirectorPosition($val->director_position);
            $literature->setFullName(html_entity_decode($val->full_name));
            $literature->setFromDate(new \DateTime(date('r', strtotime($val->from_date))));

            $entityManager->flush();
            $entityManager->persist($literature);
        }
        return $this->render('teleport/index.html.twig', [
            'out' => 'Imported'
        ]);
    }
}

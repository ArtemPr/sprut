<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Discipline;
use App\Entity\FederalStandart;
use App\Entity\FederalStandartCompetencies;
use App\Entity\Literature;
use App\Entity\LiteratureCategory;
use App\Entity\MasterProgram;
use App\Entity\ProfStandarts;
use App\Entity\ProfStandartsActivities;
use App\Entity\ProfStandartsCompetences;
use App\Entity\ProgramType;
use App\Entity\TrainingCenters;
use App\Entity\TrainingCentersRequisites;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Класс для импорта данных
 */
class TeleportController extends AbstractController
{

    #[Route('/teleport/cat', name: 'app_teleport_catt')]
//    public function add_category(ManagerRegistry $doctrine)
//    {
//        $file = $_SERVER['DOCUMENT_ROOT'] . '/tmp/category.txt';
//        $file = file($file);
//        $file = array_unique($file);
//        foreach ($file as $item) {
//            $item  = trim($item);
//            if(empty($item)) {
//                continue;
//            }
//            $cat = new Category();
//            $cat->setName($item);
//
//
//            $entityManager = $doctrine->getManager();
//            $entityManager->persist($cat);
//            $entityManager->flush();
//        }
//    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     *                  Продукты
     */
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
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }
            $program = $entityManager->getRepository(MasterProgram::class)->find($val->program_id);

            $type = $entityManager->getRepository(ProgramType::class)->find($val->type);

            if (empty($program)) {
                $program = new MasterProgram();
                $program->setId($val->program_id);
            }

            if ($val->additional_flag == '1') {
                $additional_flag = true;
            } else {
                $additional_flag = false;
            }

            $program->setName($val->name);
            $program->setLengthHour($val->hours_total);
            $program->setLengthWeek($val->weeks_total);
            $program->setProgramType($type);
            $program->setLengthWeekShort($val->weeks_short_total ?? 0);
            $program->setAdditionalFlag($additional_flag);

            $entityManager->persist($program);
            $entityManager->flush();
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a href="/teleport/books_sections">next books_sections</a>',
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     *                  Категории литературы
     */
    #[Route('/teleport/books_sections', name: 'app_books_sections')]
    public function getLiteratureCategory(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=books_sections&amp;p=4');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
            $program = $entityManager->getRepository(LiteratureCategory::class)->find($val->books_section_id);
            if (empty($program)) {
                $literature = $entityManager->getRepository(LiteratureCategory::class)->find($val->books_section_id);
                if (!empty($data)) {
                    $data = new LiteratureCategory();
                    $data->setId($val->books_section_id);
                }

                $literature->setName($val->name);
                $literature->setId($val->books_section_id);

                $entityManager->persist($literature);
                $entityManager->flush();
            }
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a href="/teleport/books">next books</a>',
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     *                  Литература
     */
    #[Route('/teleport/books', name: 'app_books')]
    public function addLiterature(ManagerRegistry $doctrine): Response
    {
        $out = 0;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=book');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {

            $literature = $entityManager->getRepository(Literature::class)->find($val->book_id);
            if (!empty($data)) {
                $data = new Literature();
                $data->setId($val->book_id);
            }

            $literature->setName($val->name);
            $literature->setId($val->book_id);
            $literature->setType($val->type);
            $literature->setAuthors($val->authors);
            $literature->setDescription($val->description);
            $literature->setYear($val->year);
            $literature->setLink($val->url);

            $entityManager->persist($literature);
            $entityManager->flush();
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a href="/teleport/training_centre">next training_centre</a>',
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     *                  Учебные центры
     */
    #[Route('/teleport/training_centre', name: 'app_tr')]
    public function addTrainingCentre(ManagerRegistry $doctrine): Response
    {
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=training_centers');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
            $data = $entityManager->getRepository(TrainingCenters::class)->find($val->training_center_id);
            if (!empty($data)) {
                $data = new TrainingCenters();
                $data->setId($val->training_center_id);
            }
            $data->setName(html_entity_decode($val->name));
            $data->setPhone($val->phone);
            $data->setEmail($val->email);
            $data->setUrl($val->url);
            $data->setExternalUploadBakalavrmagistrId((string)$val->external_upload_bakalavrmagistr_id);
            $data->setExternalUploadSdoId((string)$val->external_upload_sdo_id);

            $entityManager->persist($data);
            $entityManager->flush();
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a href="/teleport/training_centre_rq">next training_centre_rq</a>',
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     *
     * @return Response
     *
     * @throws \Exception
     *                    Реквизиты учебных центров
     */
    #[Route('/teleport/training_centre_rq', name: 'app_tr_rq')]
    public function getTrainingCentreRq(ManagerRegistry $doctrine): Response
    {
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=training_center_organizations');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        foreach ($data_item as $val) {
            $tc = $entityManager->getRepository(TrainingCenters::class)->find($val->training_center_id);

            $data = $entityManager->getRepository(TrainingCentersRequisites::class)->find($val->training_center_organization_id);

            if (empty($data)) {
                $data = new TrainingCentersRequisites();
                $data->setId($val->training_center_organization_id);
            }

            $data->setTrainingCentre($tc);
            $data->setDirector($val->director);
            $data->setCity($val->city);
            $data->setShortName(html_entity_decode($val->short_name));
            $data->setAddress(html_entity_decode($val->address));
            $data->setDirectorPosition($val->director_position);
            $data->setFullName(html_entity_decode($val->full_name));
            $data->setFromDate(new \DateTime(date('r', strtotime($val->from_date))));

            $entityManager->persist($data);
            $entityManager->flush();
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a href="/teleport/discipline">next discipline</a>',
        ]);
    }

    #[Route('/teleport/discipline', name: 'app_discipline_rq')]
    public function getDiscipline(ManagerRegistry $doctrine): Response
    {
        $on_page = 2000;

        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=discipline');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();

        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }

            $data = $entityManager->getRepository(Discipline::class)->find($val->discipline_id);

            if (empty($data)) {
                $data = new Discipline();
                $data->setId($val->discipline_id);
            }

            $data->setName($val->name)
                ->setType($val->type)
                ->setComment($val->comment)
                ->setDocxOldDocFileName($val->docx_testing_file_name)
                ->setDocxTestingFileName($val->docx_old_doc_file_name)
                ->setPractice($val->practice)
                ->setPracticumFlag($val->practicum_flag)
                ->setPurpose($val->purpose)
                ->setStatus($val->status);

            $entityManager->persist($data);
            $entityManager->flush();
        }

        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/discipline?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/federal_standart">next federal_standart</a>',
            ]);
        }
    }

    #[Route('/teleport/federal_standart', name: 'app_federal_standart')]
    public function getFederalStandart(ManagerRegistry $doctrine): Response
    {
        $on_page = 2000;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=fed_standards');
        $data_item = json_decode($data_item);
        $entityManager = $doctrine->getManager();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $page = 1;
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {

            if ($key < $start || $key > $end) {
                continue;
            }
            $data = $entityManager->getRepository(FederalStandart::class)->find($val->fed_standard_id);
            if (empty($data)) {
                $data = new FederalStandart();
                $data->setId($val->fed_standard_id);
            }
            if ($val->archive_flag === null) {
                $val->archive_flag = 0;
            }
            $data->setName($val->name)
                ->setActive((bool)$val->archive_flag)
                ->setShortName($val->short_name);

            $entityManager->persist($data);
            $entityManager->flush();
        }
        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/federal_standart?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/fed_standard_competences">next fed_standard_competences</a>',
            ]);
        }
    }

    #[Route('/teleport/fed_standard_competences', name: 'app_fed_standard_competences')]
    public function getFederalStandartCompetencies(ManagerRegistry $doctrine): Response
    {
        $on_page = 3000;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=fed_standard_competences');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $page = 1;
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }
            $data = $entityManager->getRepository(FederalStandartCompetencies::class)->find($val->fed_standard_competence_id);
            if (empty($data)) {
                $data = new FederalStandartCompetencies();
                $data->setId($val->fed_standard_competence_id);
            }

            $f_s = $entityManager->getRepository(FederalStandart::class)->find($val->fed_standard_id);

            $data->setName($val->name);
            $data->setCode($val->code);
            $data->setNumber((int)$val->number);
            $data->setFederalStandart($f_s);

            $entityManager->persist($data);
            $entityManager->flush();
        }
        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/fed_standard_competences?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/prof_standarts">next prof_standarts</a>',
            ]);
        }
    }

    #[Route('/teleport/prof_standarts', name: 'app_prof_standarts')]
    public function getProfStandart(ManagerRegistry $doctrine): Response
    {
        $on_page = 3000;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=prof_standarts');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $page = 1;
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }
            $data = $entityManager->getRepository(ProfStandarts::class)->find($val->prof_standard_id);
            if (empty($data)) {
                $data = new ProfStandarts();
                $data->setId($val->prof_standard_id);
            }

            $data->setName($val->name);
            $data->setShortName($val->short_name);
            $data->setArchiveFlag('1' == $val->archive_flag ? true : false);

            $entityManager->persist($data);
            $entityManager->flush();
        }
        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/prof_standarts?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/prof_standarts_activities">next prof_standarts_activities</a>',
            ]);
        }
    }

    #[Route('/teleport/prof_standarts_activities', name: 'prof_standarts_activities')]
    public function getProfStandartActivities(ManagerRegistry $doctrine): Response
    {
        $on_page = 3000;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=prof_standard_activities');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $page = 1;
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }
            $data = $entityManager->getRepository(ProfStandartsActivities::class)->find($val->prof_standard_activity_id);
            if (empty($data)) {
                $data = new ProfStandartsActivities();
                $data->setId($val->prof_standard_activity_id);
            }

            $f_s = $entityManager->getRepository(ProfStandarts::class)->find($val->prof_standard_id);

            $data->setName($val->name);
            $data->setProfStandartId($f_s);
            $data->setNumber($val->number);

            $entityManager->persist($data);
            $entityManager->flush();
        }
        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/prof_standarts_activities?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/prof_standard_competences">next prof_standard_competences</a>',
            ]);
        }
    }

    #[Route('/teleport/prof_standard_competences', name: 'prof_standard_competences')]
    public function getProfStandartCompetences(ManagerRegistry $doctrine): Response
    {
        $on_page = 3000;
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=prof_standard_competences');
        $data_item = json_decode($data_item);

        $entityManager = $doctrine->getManager();
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
            if (!is_numeric($page) || $page < 0) {
                $page = 1;
            }
            $start = ($page * $on_page) - 1;
            $end = $start + $on_page;
        } else {
            $page = 1;
            $start = 0;
            $end = count($data_item);
        }

        foreach ($data_item as $key => $val) {
            if ($key < $start || $key > $end) {
                continue;
            }
            $data = $entityManager->getRepository(ProfStandartsCompetences::class)->find($val->prof_standard_competence_id);
            if (empty($data)) {
                $data = new ProfStandartsCompetences();
                $data->setId($val->prof_standard_competence_id);
            }

            $f_s = $entityManager->getRepository(ProfStandartsActivities::class)->find($val->prof_standard_activity_id);

            $data->setName($val->name);
            $data->setProfstandartActivities($f_s);
            $data->setNumber($val->number);

            $entityManager->persist($data);
            $entityManager->flush();
        }
        if (ceil((int)$data_item / (int)$on_page) >= ((int)$page + 1)) {
            return $this->redirect('https://127.0.0.1:8000/teleport/prof_standard_competences?page=' . ($page + 1));
        } else {
            return $this->render('teleport/index.html.twig', [
                'out' => '<a href="/teleport/program_fed_standard_links">next program_fed_standard_links</a>',
            ]);
        }
    }


    #[Route('/teleport/program_fed_standard_links', name: 'import_program_fros')]
    public function import_program_fros(ManagerRegistry $doctrine): Response
    {
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=program_fed_standard_links');
        $data_item = json_decode($data_item);

        foreach ($data_item as $key => $val) {

            $fgos = $doctrine->getRepository(FederalStandart::class)->find($val->fed_standard_id);
            $data = $doctrine->getRepository(MasterProgram::class)->find($val->program_id);
            if (!is_null($fgos) && !is_null($data)) {
                $data->addFederalStandart($fgos);
                $entityManager = $doctrine->getManager();
                $entityManager->persist($data);
                $entityManager->flush();
            }
        }

        return $this->render('teleport/index.html.twig', [
            'out' => '<a hreh="/teleport/program_prof_standard_links">next program_prof_standard_links</a>',
        ]);
    }


    #[Route('/teleport/program_prof_standard_links', name: 'program_prof_standard_links')]
    public function program_prof_standard_links(ManagerRegistry $doctrine): Response
    {
        $data_item = file_get_contents('http://metodistam.niidpo.ru/run_transport.php?t=program_prof_standard_links');
        $data_item = json_decode($data_item);

        foreach ($data_item as $key => $val) {

            $ps = $doctrine->getRepository(ProfStandarts::class)->find($val->prof_standard_id);
            $data = $doctrine->getRepository(MasterProgram::class)->find($val->program_id);
            if (!is_null($ps) && !is_null($data)) {
                $data->addProfStandart($ps);
                $entityManager = $doctrine->getManager();
                $entityManager->persist($data);
                $entityManager->flush();
            }
        }

        return $this->render('teleport/index.html.twig', [
            'out' => 'End',
        ]);
    }
}

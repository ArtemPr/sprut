<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\EmployerRequirements;
use App\Entity\FederalStandart;
use App\Entity\MasterProgram;
use App\Entity\PotentialJobs;
use App\Entity\ProgramType;
use App\Repository\MasterProgramRepository;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiProgramController extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private MasterProgramRepository $master_programm,
        private ManagerRegistry $doctrine
    ) {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function getProgramInfo()
    {
        $result = $this->master_programm->getApiProgramInfo();

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($result ?? ['error' => 'no results']),
        ]);
    }

    public function getProgramsList() : Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $page = (int) ($request->get('page') ?? 1);
        if ($page > 0) {
            $page = $page - 1;
        } else {
            return $this->render('api/index.html.twig', ['out' => $this->convertJson(['error' => 'no results'])]);
        }
        $max_result = $request->get('max_result') ?? null;

        $param = $request->get('param') ?? null;

        $result = $this->master_programm->getProgramList((int) $page, $max_result, $param);

        foreach ($result as $key => $value) {
            $result[$key]['program_type'] = $result[$key]['program_type']['id'];
        }

        foreach ($result as $val) {
            $out[] = [
                'program_id' => $val['id'] ?? false,
                'unique_id' => $val['id'] ?? false,
                'program_name' => \html_entity_decode($val['name'] ?? ''),
                'length_hour' => $val['length_hour'] ?? false,
                'length_week' => $val['length_week'] ?? false,
                'program_type' => $val['program_type'] ?? false,
            ];
        }

        return $this->json($out ?? []);
    }

    public function getProgram(int $id) : Response
    {
        $out = $this->master_programm->getProgram($id);

        return $this->json($out ?? []);
    }

    public function add() : Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $type = !empty($data['type']) ? $this->doctrine->getRepository(ProgramType::class)->find($data['type']) : null;
        $fgos = !empty($data['fgos']) ? $this->doctrine->getRepository(FederalStandart::class)->find($data['fgos']) : null;
        $program = new MasterProgram();
        $program->setName(trim($data['name']));
        $program->setActive(!empty($data['active']) ? true : false);
        $program->setStatus('new');
        $program->setProgramType($type);

        $program->addFederalStandart($fgos);
        if (!empty($data['employer'])) {
            foreach ($data['employer'] as $emp) {
                $employer = $this->doctrine->getRepository(EmployerRequirements::class)->find($emp);
                $program->addEmployerRequirement($employer);
            }
        }
        if (!empty($data['potential'])) {
            foreach ($data['potential'] as $pot) {
                $potential = $this->doctrine->getRepository(PotentialJobs::class)->find($pot);
                $program->addPotentialJob($potential);
            }
        }

        $program->setLengthHour(0);
        $program->setLengthWeekShort(0);
        $program->setLengthWeek(0);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($program);
        $entityManager->flush();
        $lastId = $program->getId();
        $this->logAction('add_program', '??????????????????', '???????????????? ?????????????????? '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update() : Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $type = !empty($data['type']) ? $this->doctrine->getRepository(ProgramType::class)->find($data['type']) : null;
        // $fgos = !empty($data['fgos']) ? $this->doctrine->getRepository(FederalStandart::class)->find($data['fgos']) : null;

        $program = $this->doctrine->getRepository(MasterProgram::class)->find($data['id']);
        $program->setName(trim($data['name']));
        $program->setActive(!empty($data['active']) ? true : false);
        $program->setProgramType($type);
        $program->setStatus($data['status'] ?? 'new');

        // $program->addFederalStandart($fgos);
        if (!empty($data['employer'])) {
            foreach ($program->getEmployerRequirements() as $employerRequirement) {
                $program->removeEmployerRequirement($employerRequirement);
            }
            foreach ($data['employer'] as $emp) {
                $employer = $this->doctrine->getRepository(EmployerRequirements::class)->find($emp);
                $program->addEmployerRequirement($employer);
            }
        }
        if (!empty($data['potential'])) {
            foreach ($program->getPotentialJobs() as $potentialJob) {
                $program->removePotentialJob($potentialJob);
            }
            foreach ($data['potential'] as $pot) {
                $potential = $this->doctrine->getRepository(PotentialJobs::class)->find($pot);
                $program->addPotentialJob($potential);
            }
        }
        $program->setLengthHour(0);
        $program->setLengthWeekShort(0);
        $program->setLengthWeek(0);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($program);
        $entityManager->flush();

        $this->logAction('update_program', '??????????????????', $data['id'].' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}

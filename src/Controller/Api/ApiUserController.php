<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Departament;
use App\Entity\Loger;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiUserController extends AbstractController
{
    use ApiService;

    public function __construct(
        private UserRepository $userRepository,
        private ManagerRegistry $doctrine
    ) {
    }

    /**
     * @param $id
     *
     * @return Response
     */
    #[Route('/user/{id}', name: 'api_get_user', methods: ['GET'])]
    public function getUserInfo($id): Response
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_user')) {
            return $this->json(['error' => 'error auth']);
        }

        $result = $this->userRepository->find($id);

        return $this->json($result ?? []);
    }

    /**
     * @return Response
     */
    #[Route('/user', name: 'api_get_user_list', methods: ['GET'])]
    public function getUserList(): Response
    {
        $param = [];

        if (false === $this->getAuth('ROLE_API_USER', 'api_get_users')) {
            return $this->json(['error' => 'error auth']);
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $result = $this->userRepository->findBy($param);

        return $this->json($result ?? []);
    }

    /**
     * @return Response
     */
    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        if (!empty($data['password']) && !empty($data['password2']) && $data['password'] === $data['password2']) {

            $user = new User();
            $user->setUsername($data['username'] ? trim($data['username']) : '');
            $user->setPatronymic($data['patronymic'] ? trim($data['patronymic']) : '');
            $user->setSurname($data['surname'] ? trim($data['surname']) : '');

            $user->setEmail($data['email'] ? trim($data['email']) : '');
            $user->setPhone($data['phone'] ? trim($data['phone']) : '');
            $user->setActivity(!empty($data['activity']) ? true : false);

            $user->setApiHash(md5($data['email'] . $data['username']));
            $user->setSkype($data['skype'] ? trim($data['skype']) : '');
            $user->setPosition($data['position'] ? trim($data['position']) : '');
            $user->setRoles(['ROLE_USER']);

            $user->setDelete(false);

            $city = !empty($data['city']) ?
                $this->doctrine->getRepository(City::class)->find($data['city'])
                :
                $this->doctrine->getRepository(City::class)->find(0);
            $user->setCity($city);

            $departament = !empty($data['departament']) ?
                $this->doctrine->getRepository(Departament::class)->find($data['departament'])
                :
                $this->doctrine->getRepository(Departament::class)->find(0);
            $user->setDepartament($departament);

            $password = $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->setPassword($password);

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $lastId = $user->getId();


            $loger = new Loger();
            $loger->setTime(new \DateTime());
            $loger->setAction('add_user');
            $loger->setUserLoger($this->getUser());
            $loger->setIp($request->server->get('REMOTE_ADDR'));
            $loger->setChapter('Пользователи');
            $loger->setComment('Добавлен пользователь ' . $lastId . ' ' . $data['username']);
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($loger);
            $entityManager->flush();

            return $this->json(['result' => 'success', 'id' => $lastId]);
        } else {
            return $this->json(['result' => 'error']);
        }
    }

    public function hide(int $id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $kafedra = $this->doctrine->getRepository(User::class)->find((int)$id);
        $kafedra->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(User::class)->find((int)$id);

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('delete_user');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Пользователи');
        $loger->setComment($id . ' ' . $data->getUsername());
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id'=>$id]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();


        $user = $this->doctrine->getRepository(User::class)->find($data['id']);

        $user->setUsername($data['username'] ? trim($data['username']) : '');
        $user->setPatronymic($data['patronymic'] ? trim($data['patronymic']) : '');
        $user->setSurname($data['surname'] ? trim($data['surname']) : '');

        $user->setEmail($data['email'] ? trim($data['email']) : '');
        $user->setPhone($data['phone'] ? trim($data['phone']) : '');
        $user->setActivity(!empty($data['activity']) ? true : false);

        $user->setApiHash(md5($data['email'] . $data['username']));
        $user->setSkype($data['skype'] ? trim($data['skype']) : '');
        $user->setPosition($data['position'] ? trim($data['position']) : '');
        $user->setRoles(['ROLE_USER']);

        $user->setDelete(false);

        $city = !empty($data['city']) ?
            $this->doctrine->getRepository(City::class)->find($data['city'])
            :
            $this->doctrine->getRepository(City::class)->find(0);
        $user->setCity($city);

        $departament = !empty($data['departament']) ?
            $this->doctrine->getRepository(Departament::class)->find($data['departament'])
            :
            $this->doctrine->getRepository(Departament::class)->find(0);
        $user->setDepartament($departament);


        if (!empty($data['password']) && !empty($data['password2']) && $data['password'] === $data['password2']) {
            $password = $this->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->setPassword($password);
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();


        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_user');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Пользователи');
        $loger->setComment('Добавлен пользователь ' . $data['id'] . ' ' . $data['username']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}

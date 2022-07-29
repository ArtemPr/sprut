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
use App\Service\LoggerService;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiUserController extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private UserRepository $userRepository,
        private ManagerRegistry $doctrine,
        private MailService $mailService
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

            $username = $data['username'] ? trim($data['username']) : '';
            $patronymic = $data['patronymic'] ? trim($data['patronymic']) : '';
            $surname = $data['surname'] ? trim($data['surname']) : '';

            $user->setFullname("$username $surname $patronymic");

            $user->setEmail($data['email'] ? trim($data['email']) : '');
            $user->setPhone($data['phone'] ? trim($data['phone']) : '');
            $user->setActivity(!empty($data['activity']) ? true : false);

            $user->setApiHash(md5($data['email'] . $data['username']));
            $user->setSkype($data['skype'] ? trim($data['skype']) : '');
            $user->setPosition($data['position'] ? trim($data['position']) : '');

            $user->setCreatedAt(new \DateTime());

            if (empty($data['roles'])) {
                $data['roles'][] = 'ROLE_USER';
            } else {
                if (!in_array('ROLE_USER', $data['roles'])) {
                    $data['roles'][] = 'ROLE_USER';
                }
            }
            $user->setRoles($data['roles']);

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

            $this->logAction('add_user', 'Пользователи', 'Добавлен пользователь ' . $lastId . ' ' . $data['username']);

            $message = $this->renderView('mail/new_user.html.twig',
                [
                    'email' => $data['email'],
                    'password' => $data['password']
                ]
            );

            $this->mailService->sendMail(
                from: 'sender@i-spo.ru',
                to: $data['email'],
                subject: 'Создание аккаунта в системе СПРУТ',
                message: $message
            );

            return $this->json(['result' => 'success', 'id' => $lastId]);
        } else {
            return $this->json(
                [
                    'result' => 'error',
                    'massege' => 'password resolve'
                ]
            );
        }
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();


        $user = $this->doctrine->getRepository(User::class)->find($data['id']);

        $user->setUsername($data['username'] ? trim($data['username']) : '');
        $user->setPatronymic($data['patronymic'] ? trim($data['patronymic']) : '');
        $user->setSurname($data['surname'] ? trim($data['surname']) : '');

        $username = $data['username'] ? trim($data['username']) : '';
        $patronymic = $data['patronymic'] ? trim($data['patronymic']) : '';
        $surname = $data['surname'] ? trim($data['surname']) : '';

        $user->setFullname("$username $surname $patronymic");

        $user->setEmail($data['email'] ? trim($data['email']) : '');
        $user->setPhone($data['phone'] ? trim($data['phone']) : '');
        $user->setActivity(!empty($data['activity']) ? true : false);

        $user->setApiHash(md5($data['email'] . $data['username']));
        $user->setSkype($data['skype'] ? trim($data['skype']) : '');
        $user->setPosition($data['position'] ? trim($data['position']) : '');

        $user->setUpdatedAt(new \DateTime());

        if (!in_array('ROLE_USER', $data['roles'])) {
            $data['roles'][] = 'ROLE_USER';
        }
        $user->setRoles($data['roles']);

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

        $this->logAction('update_user', 'Пользователи', 'Обновлены данные пользователя ' . $data['id'] . ' ' . $data['username']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide(int $id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $userEntity = $this->doctrine->getRepository(User::class)->find((int)$id);
        $userEntity->setDelete(true);
        $userEntity->setFixDelete($userEntity->getEmail());

        $fix_user_email = time() . '_' . $userEntity->getEmail();
        $userEntity->setEmail($fix_user_email);

        $userEntity->setDeletedAt(new \DateTime());

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($userEntity);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(User::class)->find((int)$id);

        $this->logAction('delete_user', 'Пользователи', $id . ' ' . $data->getUsername());

        return $this->json(['result' => 'success', 'id'=>$id]);
    }
}

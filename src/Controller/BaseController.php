<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

/**
 *
 */
class BaseController extends AbstractController
{

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $get_data;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        private Security $security
    ) {
        $this->managerRegistry = $managerRegistry;

        $this->request = new Request(
            query: $_GET,
            request: $_POST,
            server: $_SERVER,
            cookies: $_COOKIE,
            files: $_FILES
        );

        $this->get_data = $this->request->query->all();

        $this->writeCurrentPage();
    }

    /**
     * @return void
     */
    private function writeCurrentPage(): void
    {
        $current_page = $this->getCurrentPage();
        $user_id = $this->getUserId();
        if(!empty($current_page) && !empty($user_id)) {
            $userRepository = $this->managerRegistry->getRepository(User::class)->find($user_id);
            $userRepository->setLastPage($current_page);

            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($userRepository);
            $entityManager->flush();
        }
    }

    /**
     * @return int|null
     */
    private function getUserId(): ?int
    {
        $user = $this->security->getUser();
        if (!empty($user) && !empty($user->getId())) {
            return $user->getId();
        }
        return null;
    }

    /**
     * @return string|null
     */
    private function getCurrentPage(): ?string
    {
        return $this->request->server->get('REQUEST_URI');
    }
}

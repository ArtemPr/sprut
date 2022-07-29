<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
namespace App\EventListener;

use App\Entity\Loger;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class CustomLogoutListener
{
    use LoggerService;
    public function __construct(
        private readonly ManagerRegistry $doctrine
    )
    {
    }

    private $user;

    /**
     * @param LogoutEvent $logoutEvent
     * @return void
     */
    #[NoReturn]
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent): void
    {
        if ($logoutEvent->getToken()) {
            $this->user = $logoutEvent->getToken()->getUser();
            $this->logAction('logout', 'Пользователи', null);
        }
    }

    private function getUser()
    {
        return $this->user;
    }
}

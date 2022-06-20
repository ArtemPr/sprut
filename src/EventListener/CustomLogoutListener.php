<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */
namespace App\EventListener;

use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class CustomLogoutListener
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    /**
     * @param LogoutEvent $logoutEvent
     * @return void
     */
    #[NoReturn]
    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $logoutEvent): void
    {
        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('logout');
        $loger->setUserLoger($logoutEvent->getToken()->getUser());
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

    }
}

<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Service;

use App\Entity\Loger;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

trait LoggerService
{
    public function logAction(string $action, User $user, string $chapter, ?string $comment)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, [], $_SERVER);

        $logger = new Loger();
        $logger->setTime(new \DateTime());
        $logger->setAction($action);
        $logger->setUserLoger($user);
        $logger->setIp($request->server->get('REMOTE_ADDR'));
        $logger->setChapter($chapter);
        $logger->setComment($comment ?? '');

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($logger);
        $entityManager->flush();
    }
}

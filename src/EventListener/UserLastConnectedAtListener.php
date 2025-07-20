<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class UserLastConnectedAtListener
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
        
    }
    #[AsEventListener(event: 'security.interactive_login', priority:10)]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof User){
            if (in_array("ROLE_CLIENT", $user->getRoles(), true) && $user->getCustomer()){
                $user->getCustomer()->setLastConnectedAt(new \DateTimeImmutable());
            } elseif ($user->getCollaborator()) {
                $user->getCollaborator()->setLastConnectedAt(new \DateTimeImmutable());
            }
            $this->manager->flush();
        }
    }
}

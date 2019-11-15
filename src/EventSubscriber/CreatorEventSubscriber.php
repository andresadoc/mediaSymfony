<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advertisements;
use App\Entity\Campaign;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreatorEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this -> tokenStorage = $tokenStorage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents():?array
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function getAuthenticatedUser(ViewEvent $event)
    {
        $entity = $event -> getControllerResult();
        $method = $event -> getRequest() -> getMethod();
        $user=$this->tokenStorage->getToken()->getUser();

        if(!$user instanceof User or (!($entity instanceof Advertisements) and !($entity instanceof Campaign)) or (Request::METHOD_POST != $method)){
            return;
        }

        $entity -> setCreator($user);
    }
}
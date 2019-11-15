<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advertisements;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdvertisementEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    private $logger;

    public function __construct(TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this -> tokenStorage = $tokenStorage;
        $this -> logger = $logger;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents():?array
    {
        return [
            KernelEvents::VIEW => ['validateByMediaType', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return bool|void|null
     */
    public function validateByMediaType(ViewEvent $event)
    {
        $advertisement = $event -> getControllerResult();
        $method = $event -> getRequest() -> getMethod();

        if(!($advertisement instanceof Advertisements) or (Request::METHOD_POST != $method)){
            return;
        }

        $advertisement -> validateByMediaType();
    }
}
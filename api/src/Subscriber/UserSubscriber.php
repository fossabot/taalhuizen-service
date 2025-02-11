<?php


namespace App\Subscriber;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Service\CCService;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

class UserSubscriber implements EventSubscriberInterface
{
    private $em;
    private $params;
    private $commonGroundService;
    private $ccService;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params, CommongroundService $commonGroundService, CCService $ccService)
    {
        $this->em = $em;
        $this->params = $params;
        $this->commonGroundService = $commonGroundService;
        $this->ccService = $ccService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['user', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function user(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $contentType = $event->getRequest()->headers->get('accept');
        $route = $event->getRequest()->attributes->get('_route');
        $resource = $event->getControllerResult();

        // Lets limit the subscriber
        if ($route != 'api_user_get_collection' && $route != 'api_user_post_collection'){
            return;
        }

        // this: is only here to make sure result has a result and that this is always shown first in the response body
        $result['result'] = [];

        //handle post
        if ($route == 'api_user_post_collection' and $resource instanceof User){
            $person = $this->dtoToUser($resource);
            //make person
        }
    }

    public function dtoToUser($resource){
        if ($resource->getId()) {
            $user['id'] = $resource->getId();
        }
        $user['email'] = $resource->getEmail();
        $user['username'] = $resource->getUsername();
        $user['password'] = $resource->getPassword();
        $user['token'] = $resource->getToken();

        return $user;
    }

    private function handleResult($user) {
        return [
            'id' => $user['id'],
            'username' => $user['username']
        ];
    }
}

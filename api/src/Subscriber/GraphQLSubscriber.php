<?php


namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Taalhuis;
use App\Service\CCService;
use App\Service\UcService;
use App\Service\WRCService;
use App\Service\EDUService;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use GraphQL\GraphQL;
use GraphQL\Language\Parser;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Cache\Adapter\AdapterInterface as CacheInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GraphQLSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;
    private ParameterBagInterface $params;
    private UcService $ucService;
    private SerializerInterface $serializer;
    private CacheInterface $cache;

    public function __construct(UcService $ucService, EntityManagerInterface $em, ParameterBagInterface $params, SerializerInterface $serializer, CacheInterface $cache)
    {
        $this->em = $em;
        $this->params = $params;
        $this->ucService = $ucService;
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['login', EventPriorities::PRE_DESERIALIZE],
        ];
    }

    //@TODO Errors in correct format
    public function login(RequestEvent $event)
    {
        $content = json_decode($event->getRequest()->getContent(), true);
        $graphQL = Parser::parse($content['query']);
        if(
            $graphQL->definitions->offsetGet(0)->name->value != 'loginUser' &&
            $graphQL->definitions->offsetGet(0)->name->value != 'requestPasswordResetUser' &&
            $graphQL->definitions->offsetGet(0)->name->value != 'resetPasswordUser'
        ) {
            $auth = $event->getRequest()->headers->get('Authorization');
            if($this->checkInvalidated($auth)){
                $result['errors'][] = new Error('Token has been invalidated');
                var_dump('hi!');
                $this->throwError($event, $result);
                return;
            }
            if(strpos($auth, 'Bearer') !== false){
                $token = str_replace('Bearer ', '', $auth);
                try{
                    $payload = $this->ucService->validateJWTAndGetPayload($token);
                    if(!$this->validatePayload($payload)){
                        $result['errors'] = new Error('Token not valid');
                        $this->throwError($event, $result);
                        return;
                    }
                } catch (Exception $e){
                    $result['errors'][] = $e;
                    $this->throwError($event, $result);
                    return;
                }
            }
            else{
                $result['errors'][] = new Error('No access token provided');
                $this->throwError($event, $result);
                return;
            }
        }
    }

    public function throwError(RequestEvent $event, array $result): RequestEvent
    {
        if(isset($result)){
            $event->setResponse(new Response($this->serializer->serialize($result, 'json'), 200));
        }
        return $event;
    }

    public function checkInvalidated(string $header): bool
    {
        $item = $this->cache->getItem('invalidToken_'.md5($header));
        if($item->isHit()) {
            $value = $item->get();
            return ($value == $header);
        }
        return false;
    }

    public function validatePayload(array $payload): bool
    {
        $now = new \DateTime();
        $checks['issuer']   = isset($payload['iss']) && $payload['iss'] == $this->params->get('app_url');
        $checks['type']     = isset($payload['type']) && $payload['type'] == 'login';
        $checks['expiry']   = isset($payload['exp']) && $payload['exp'] > $now->getTimestamp();
        $checks['issuance'] = isset($payload['ias']) && $payload['ias'] < $now->getTimestamp();

        foreach ($checks as $check){
            if(!$check){
                return false;
            }
        }
        return true;
    }
}

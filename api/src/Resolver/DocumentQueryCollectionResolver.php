<?php


namespace App\Resolver;


use ApiPlatform\Core\DataProvider\ArrayPaginator;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Service\WRCService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DocumentQueryCollectionResolver implements QueryCollectionResolverInterface
{
    private WRCService $wrcService;

    public function __construct(WRCService $wrcService){
        $this->wrcService = $wrcService;
    }
    /**
     * @inheritDoc
     */
    public function __invoke(iterable $collection, array $context): iterable
    {

        $collection = $this->wrcService->getDocuments(
            key_exists('languageHouseId', $context['args']) ?
                $context['args']['languageHouseId'] :
                null,
            key_exists('providerId', $context['args']) ?
                $context['args']['providerId'] :
                null
        );
        return $this->createPaginator($collection, $context['args']);
    }

    public function createPaginator(ArrayCollection $collection, array $args){
        if(key_exists('first', $args)){
            $maxItems = $args['first'];
            $firstItem = 0;
        } elseif(key_exists('last', $args)) {
            $maxItems = $args['last'];
            $firstItem = (count($collection) - 1) - $maxItems;
        } else {
            $maxItems = count($collection);
            $firstItem = 0;
        }
        if(key_exists('after', $args)){
            $firstItem = base64_decode($args['after']);
        } elseif(key_exists('before', $args)){
            $firstItem = base64_decode($args['before']) - $maxItems;
        }
        return new ArrayPaginator($collection->toArray(), $firstItem, $maxItems);
    }
}

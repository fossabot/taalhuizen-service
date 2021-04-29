<?php


namespace App\Resolver;


use ApiPlatform\Core\DataProvider\ArrayPaginator;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\GraphQl\Resolver\QueryCollectionResolverInterface;
use App\Entity\LanguageHouse;
use App\Service\LanguageHouseService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Ramsey\Uuid\Uuid;

class LanguageHouseQueryCollectionResolver implements QueryCollectionResolverInterface
{
    private LanguageHouseService $languageHouseService;
    private EntityManagerInterface $entityManager;

    public function __construct(LanguageHouseService $languageHouseService, EntityManagerInterface $entityManager){
        $this->languageHouseService = $languageHouseService;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(iterable $collection, array $context): iterable
    {
        $result['result'] = [];

        // Get the languageHouses
        $collection = $this->languageHouseService->getLanguageHouses();
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

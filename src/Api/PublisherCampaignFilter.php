<?php


namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\User;
use App\Entity\Campaign;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

final class PublisherCampaignFilter implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @param Security $security
     * @param LoggerInterface $logger
     */
    public function __construct(Security $security, LoggerInterface $logger)
    {
        $this -> security = $security;
        $this -> logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (Campaign::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        if (!($user instanceof User)) {
            return;
        }

        $this->restrict($queryBuilder, $user);
    }

    /**
     * @inheritDoc
     */
    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        if (Campaign::class !== $resourceClass) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return;
        }

        $this->restrict($queryBuilder, $user);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param User $user
     */
    private function restrict(QueryBuilder $queryBuilder, User $user)
    {
        //$this -> logger -> info((string) $queryBuilder);
        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.publisher = :user OR %s.creator = :user', $rootAlias, $rootAlias));
        $queryBuilder->setParameter('user', $user);
        //$queryBuilder->setParameter('roles', (implode(", ", $user -> getRoles())));
    }
}
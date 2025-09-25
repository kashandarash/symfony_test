<?php

namespace App\GraphQL\Resolver;

use App\Service\QueryService;
use ArrayObject;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;
use Symfony\Bundle\SecurityBundle\Security;

class SpecialResolverMap extends ResolverMap {

  public function __construct(
    private QueryService   $queryService,
    private Security $security,
  ) {}

  /**
   * @inheritDoc
   */
  protected function map(): array {
    return [
      'SpecialQuery' => [
        self::RESOLVE_FIELD => function (
          $value,
          ArgumentInterface $args,
          ArrayObject $context,
          ResolveInfo $info
        ) {
          $user_identifier = $this->security->getUser()->getUserIdentifier();
          return match ($info->fieldName) {
            // This key should match the name from SpecialQuery in query.graphql.
            // The value is just a service with custom method.
            'hello' => $this->queryService->getHelloText($user_identifier),
            default => NULL
          };
        },
      ],
    ];
  }

}
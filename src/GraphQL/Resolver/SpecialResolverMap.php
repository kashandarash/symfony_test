<?php

namespace App\GraphQL\Resolver;

use App\Service\MutationService;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Service\QueryService;
use ArrayObject;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class SpecialResolverMap extends ResolverMap {

  public function __construct(
    private QueryService   $queryService,
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
          return match ($info->fieldName) {
            // This key should match the name from SpecialQuery in query.graphql.
            // The value is just a service with custom method.
            'hello' => $this->queryService->getHelloText(),
            default => NULL
          };
        },
      ],
    ];
  }

}
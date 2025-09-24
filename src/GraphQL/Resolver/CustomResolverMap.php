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

class CustomResolverMap extends ResolverMap {

  public function __construct(
    private QueryService   $queryService,
    private UserRepository $userRepository,
    private ArticleRepository $articleRepository,
    private MutationService $mutationService,
  ) {}

  /**
   * @inheritDoc
   */
  protected function map(): array {
    return [
      'RootQuery' => [
        self::RESOLVE_FIELD => function (
          $value,
          ArgumentInterface $args,
          ArrayObject $context,
          ResolveInfo $info
        ) {
          return match ($info->fieldName) {
            // This key should match the name from RootQuery in query.graphql.
            // The value is just a service with custom method.
            'user' => $this->userRepository->find((int) $args['id']),
            'users' => $this->userRepository->findAll(),
            'hello' => $this->queryService->getHelloText(),
            'articles' =>  $this->articleRepository->findArticles(['locale' => (string) $args['locale'], 'published' => (bool) $args['published']]),
            default => NULL
          };
        },
      ],
      'RootMutation' => [
        self::RESOLVE_FIELD => function (
          $value,
          ArgumentInterface $args,
          ArrayObject $context,
          ResolveInfo $info
        ) {
          return match ($info->fieldName) {
            'createArticle' => $this->mutationService->createArticle($args->getArrayCopy()),
            'updateArticle' => $this->mutationService->updateArticle($args->getArrayCopy()),
            default => null
          };
        },
      ],
    ];
  }

}
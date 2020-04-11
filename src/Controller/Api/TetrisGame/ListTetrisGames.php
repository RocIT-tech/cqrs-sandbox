<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Query\ListTetrisGameQuery;
use App\ReadModel\TetrisGame;
use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use function array_reduce;

/**
 * @Route(
 *     name="api_list_tetris_games",
 *     path="",
 *     methods={"GET"}
 * )
 */
class ListTetrisGames
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private RouterInterface $router;

    public function __construct(
        SerializerInterface $serializer,
        RouterInterface $router,
        MessageBusInterface $queryBus
    ) {
        $this->serializer = $serializer;
        $this->router     = $router;
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request)
    {
        $query = new ListTetrisGameQuery();

        $tetrisGames = $this->handle($query);

        $linkProvider = $request->attributes->get('_links', new GenericLinkProvider());

        $linkProvider = array_reduce($tetrisGames, function (
            GenericLinkProvider $linkProvider,
            TetrisGame $tetrisGame
        ) {
            return $linkProvider->withLink(
                new Link(
                    'preload',
                    $this->router->generate('api_fetch_tetris_games', ['tetrisGameId' => $tetrisGame->id])
                )
            );
        }, $linkProvider);

        $request->attributes->set('_links', $linkProvider);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGames, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

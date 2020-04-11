<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Query\FetchTetrisGameQuery;
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

/**
 * @Route(
 *     name="api_fetch_tetris_games",
 *     path="/{tetrisGameId}",
 *     methods={"GET"}
 * )
 */
class FetchTetrisGame
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

    public function __invoke(Request $request, string $tetrisGameId)
    {
        $query               = new FetchTetrisGameQuery();
        $query->tetrisGameId = $tetrisGameId;

        $tetrisGame = $this->handle($query);

        $linkProvider = $request->attributes->get('_links', new GenericLinkProvider());
        $request->attributes->set('_links', $linkProvider->withLink(
            new Link('preload', $this->router->generate('api_list_tetris_game_challengers', ['tetrisGameId' => $tetrisGameId]))
        ));

        return new JsonResponse(
            $this->serializer->serialize($tetrisGame, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

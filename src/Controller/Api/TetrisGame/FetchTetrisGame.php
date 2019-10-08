<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Query\FetchTetrisGameQuery;
use App\Query\FetchTetrisGameQueryHandler;
use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @var FetchTetrisGameQueryHandler
     */
    private $fetchTetrisGameQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        FetchTetrisGameQueryHandler $fetchTetrisGameQueryHandler,
        SerializerInterface $serializer,
        RouterInterface $router
    ) {
        $this->fetchTetrisGameQueryHandler = $fetchTetrisGameQueryHandler;
        $this->serializer                  = $serializer;
        $this->router                      = $router;
    }

    public function __invoke(Request $request, string $tetrisGameId)
    {
        $query               = new FetchTetrisGameQuery();
        $query->tetrisGameId = $tetrisGameId;

        $tetrisGame = ($this->fetchTetrisGameQueryHandler)($query);

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

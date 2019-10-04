<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Query\FetchTetrisGameQuery;
use App\Query\FetchTetrisGameQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_fetch_tetris_games",
 *     path="/{tetrisGameId}",
 *     methods={"GET"}
 * )
 */
class FetchTetrisGames
{
    /**
     * @var FetchTetrisGameQueryHandler
     */
    private $fetchTetrisGameQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        FetchTetrisGameQueryHandler $fetchTetrisGameQueryHandler,
        SerializerInterface $serializer
    ) {
        $this->fetchTetrisGameQueryHandler = $fetchTetrisGameQueryHandler;
        $this->serializer                  = $serializer;
    }

    public function __invoke(string $tetrisGameId)
    {
        $query               = new FetchTetrisGameQuery();
        $query->tetrisGameId = $tetrisGameId;

        $tetrisGame = ($this->fetchTetrisGameQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGame, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

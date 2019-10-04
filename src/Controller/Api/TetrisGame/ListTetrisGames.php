<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Query\ListTetrisGameQuery;
use App\Query\ListTetrisGameQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_list_tetris_games",
 *     path="",
 *     methods={"GET"}
 * )
 */
class ListTetrisGames
{
    /**
     * @var ListTetrisGameQueryHandler
     */
    private $listTetrisGameQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ListTetrisGameQueryHandler $listTetrisGameQueryHandler,
        SerializerInterface $serializer
    ) {
        $this->listTetrisGameQueryHandler = $listTetrisGameQueryHandler;
        $this->serializer                 = $serializer;
    }

    public function __invoke()
    {
        $query = new ListTetrisGameQuery();

        $tetrisGames = ($this->listTetrisGameQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGames, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

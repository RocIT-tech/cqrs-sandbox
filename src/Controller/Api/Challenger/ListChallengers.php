<?php

declare(strict_types=1);

namespace App\Controller\Api\Challenger;

use App\Query\ListTetrisGameChallengersQuery;
use App\Query\ListTetrisGameChallengersQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_list_tetris_game_challengers",
 *     path="",
 *     methods={"GET"}
 * )
 */
class ListChallengers
{
    /**
     * @var ListTetrisGameChallengersQueryHandler
     */
    private $listTetrisGameChallengersQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ListTetrisGameChallengersQueryHandler $listTetrisGameChallengersQueryHandler,
        SerializerInterface $serializer
    ) {
        $this->listTetrisGameChallengersQueryHandler = $listTetrisGameChallengersQueryHandler;
        $this->serializer                            = $serializer;
    }

    public function __invoke(string $tetrisGameId)
    {
        $query               = new ListTetrisGameChallengersQuery();
        $query->tetrisGameId = $tetrisGameId;

        $tetrisGames = ($this->listTetrisGameChallengersQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGames, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Api\Challenger;

use App\Query\ListTetrisGameChallengersQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
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
    use HandleTrait;

    private SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer,
        MessageBusInterface $queryBus
    ) {
        $this->serializer = $serializer;
        $this->messageBus = $queryBus;
    }

    public function __invoke(string $tetrisGameId)
    {
        $query               = new ListTetrisGameChallengersQuery();
        $query->tetrisGameId = $tetrisGameId;

        $tetrisGames = $this->handle($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGames, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

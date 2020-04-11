<?php

declare(strict_types=1);

namespace App\Controller\Api\Person;

use App\Query\ListTetrisGameQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_list_persons_tetris_games",
 *     path="/{id}/tetris-games",
 *     methods={"GET"}
 * )
 */
class ListTetrisGames
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private MessageBusInterface $queryBus;

    public function __construct(
        SerializerInterface $serializer,
        MessageBusInterface $queryBus
    ) {
        $this->serializer = $serializer;
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request, string $id)
    {
        $query           = new ListTetrisGameQuery();
        $query->personId = $id;

        $tetrisGames = $this->handle($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGames, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}


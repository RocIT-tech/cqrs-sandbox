<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use App\Command\CreateTetrisGameCommand;
use App\Query\FetchTetrisGameQuery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_create_tetris_games",
 *     path="",
 *     methods={"POST"}
 * )
 */
class CreateTetrisGame
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private MessageBusInterface $commandBus;

    public function __construct(
        SerializerInterface $serializer,
        MessageBusInterface $commandBus,
        MessageBusInterface $queryBus
    ) {
        $this->serializer = $serializer;
        $this->commandBus = $commandBus;
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request)
    {
        $command     = new CreateTetrisGameCommand();
        $command->id = Uuid::uuid4()->toString();

        /** @var CreateTetrisGameCommand $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            CreateTetrisGameCommand::class,
            $request->getRequestFormat(JsonEncoder::FORMAT),
            [
                AbstractObjectNormalizer::OBJECT_TO_POPULATE       => $command,
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );

        $this->commandBus->dispatch($command);

        $query               = new FetchTetrisGameQuery();
        $query->tetrisGameId = $command->id;

        $tetrisGame = $this->handle($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGame, JsonEncoder::FORMAT),
            Response::HTTP_CREATED,
            [],
            true
        );
    }
}

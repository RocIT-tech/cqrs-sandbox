<?php

declare(strict_types=1);

namespace App\Controller\Api\Challenger;

use App\Command\CreateChallengersCommand;
use App\Command\CreateChallengersCommand\Challenger;
use App\Query\ListTetrisGameChallengersQuery;
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
use function array_walk;
use function sprintf;

/**
 * @Route(
 *     name="api_add_tetris_game_challengers",
 *     path="",
 *     methods={"POST"}
 * )
 */
class AddChallengers
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

    public function __invoke(Request $request, string $tetrisGameId)
    {
        $command               = new CreateChallengersCommand();
        $command->tetrisGameId = $tetrisGameId;
        $command->challengers  = $this->serializer->deserialize(
            $request->getContent(),
            sprintf('%s[]', Challenger::class),
            $request->getRequestFormat(JsonEncoder::FORMAT),
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );

        array_walk(
            $command->challengers,
            static function (Challenger $challenger): void {
                $challenger->id = $challenger->id ?: Uuid::uuid4()->toString();
            }
        );

        $this->commandBus->dispatch($command);

        $query               = new ListTetrisGameChallengersQuery();
        $query->tetrisGameId = $command->tetrisGameId;

        $challengers = $this->handle($query);

        return new JsonResponse(
            $this->serializer->serialize($challengers, JsonEncoder::FORMAT),
            Response::HTTP_CREATED,
            [],
            true
        );
    }
}

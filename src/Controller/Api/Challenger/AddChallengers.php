<?php

declare(strict_types=1);

namespace App\Controller\Api\Challenger;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Command\CreateChallengersCommand;
use App\Command\CreateChallengersCommandHandler;
use App\Query\ListTetrisGameChallengersQuery;
use App\Query\ListTetrisGameChallengersQueryHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @var CreateChallengersCommandHandler
     */
    private $createChallengersCommandHandler;

    /**
     * @var ListTetrisGameChallengersQueryHandler
     */
    private $listTetrisGameChallengersQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        CreateChallengersCommandHandler $createChallengersCommandHandler,
        ListTetrisGameChallengersQueryHandler $listTetrisGameChallengersQueryHandler,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->createChallengersCommandHandler       = $createChallengersCommandHandler;
        $this->listTetrisGameChallengersQueryHandler = $listTetrisGameChallengersQueryHandler;
        $this->serializer                            = $serializer;
        $this->validator                             = $validator;
    }

    public function __invoke(Request $request, string $tetrisGameId)
    {
        $command               = new CreateChallengersCommand();
        $command->tetrisGameId = $tetrisGameId;
        $command->challengers  = $this->serializer->deserialize(
            $request->getContent(),
            sprintf('%s[]', CreateChallengersCommand\Challenger::class),
            $request->getRequestFormat(JsonEncoder::FORMAT),
            [
                AbstractObjectNormalizer::OBJECT_TO_POPULATE       => $command->challengers,
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );

        array_walk(
            $command->challengers,
            static function (CreateChallengersCommand\Challenger $challenger): void {
                $challenger->id = $challenger->id ?: Uuid::uuid4()->toString();
            }
        );

        $this->validator->validate($command);

        ($this->createChallengersCommandHandler)($command);

        $query               = new ListTetrisGameChallengersQuery();
        $query->tetrisGameId = $command->tetrisGameId;

        $challengers = ($this->listTetrisGameChallengersQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($challengers, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

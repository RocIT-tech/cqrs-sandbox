<?php

declare(strict_types=1);

namespace App\Controller\Api\TetrisGame;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Command\CreateTetrisGameCommand;
use App\Command\CreateTetrisGameCommandHandler;
use App\Query\FetchTetrisGameQuery;
use App\Query\FetchTetrisGameQueryHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @var CreateTetrisGameCommandHandler
     */
    private $createTetrisGameCommandHandler;

    /**
     * @var FetchTetrisGameQueryHandler
     */
    private $fetchTetrisGameQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        CreateTetrisGameCommandHandler $createTetrisGameCommandHandler,
        FetchTetrisGameQueryHandler $fetchTetrisGameQueryHandler,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->createTetrisGameCommandHandler = $createTetrisGameCommandHandler;
        $this->fetchTetrisGameQueryHandler = $fetchTetrisGameQueryHandler;
        $this->serializer                     = $serializer;
        $this->validator                      = $validator;
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

        $this->validator->validate($command);

        ($this->createTetrisGameCommandHandler)($command);

        $query               = new FetchTetrisGameQuery();
        $query->tetrisGameId = $command->id;

        $tetrisGame = ($this->fetchTetrisGameQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($tetrisGame, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

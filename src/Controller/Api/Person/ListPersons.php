<?php

declare(strict_types=1);

namespace App\Controller\Api\Person;

use App\Query\ListPersonsQuery;
use App\Query\ListPersonsQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(
 *     name="api_list_persons",
 *     path="",
 *     methods={"GET"}
 * )
 */
class ListPersons
{
    /**
     * @var ListPersonsQueryHandler
     */
    private $listPersonsQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ListPersonsQueryHandler $listPersonsQueryHandler,
        SerializerInterface $serializer
    ) {
        $this->listPersonsQueryHandler = $listPersonsQueryHandler;
        $this->serializer              = $serializer;
    }

    public function __invoke()
    {
        $query = new ListPersonsQuery();

        $persons = ($this->listPersonsQueryHandler)($query);

        return new JsonResponse(
            $this->serializer->serialize($persons, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

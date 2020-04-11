<?php

declare(strict_types=1);

namespace App\Controller\Api\Person;

use App\Query\ListTopPersonsQuery;
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
 *     name="api_list_top_persons",
 *     path="/top",
 *     methods={"GET"}
 * )
 */
class ListTopPersons
{
    use HandleTrait;

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer, MessageBusInterface $queryBus)
    {
        $this->serializer = $serializer;
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request)
    {
        $query        = new ListTopPersonsQuery();
        $query->limit = $request->query->get('limit', null);

        $topPersons = $this->handle($query);

        return new JsonResponse(
            $this->serializer->serialize($topPersons, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

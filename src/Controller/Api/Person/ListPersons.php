<?php

declare(strict_types=1);

namespace App\Controller\Api\Person;

use App\Query\ListPersonsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use function ceil;

/**
 * @Route(
 *     name="api_list_persons",
 *     path="",
 *     methods={"GET"}
 * )
 */
class ListPersons
{
    use HandleTrait;

    private SerializerInterface $serializer;

    private RouterInterface $router;

    public function __construct(
        SerializerInterface $serializer,
        RouterInterface $router,
        MessageBusInterface $queryBus
    ) {
        $this->serializer = $serializer;
        $this->router     = $router;
        $this->messageBus = $queryBus;
    }

    public function __invoke(Request $request)
    {
        $query       = new ListPersonsQuery();
        $query->page = $request->query->getInt('page', $query->page);
        $query->max  = $request->query->getInt('max', $query->max);

        $persons = $this->handle($query);

        if ($persons->count > $query->max) {
            $persons->_links['next'] = $this->router->generate('api_list_persons', [
                    'max'  => $query->max,
                    'page' => ($query->page + 1),
                ]
            );
            $persons->_links['last'] = $this->router->generate('api_list_persons', [
                    'max'  => $query->max,
                    'page' => ceil($persons->count / $query->max),
                ]
            );
        }

        if ($query->page > 1) {
            $persons->_links['prev']  = $this->router->generate('api_list_persons', [
                    'max'  => $query->max,
                    'page' => ($query->page - 1),
                ]
            );
            $persons->_links['first'] = $this->router->generate('api_list_persons', [
                    'max'  => $query->max,
                    'page' => 1,
                ]
            );
        }

        return new JsonResponse(
            $this->serializer->serialize($persons, JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

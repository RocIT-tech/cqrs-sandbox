<?php

declare(strict_types=1);

namespace App\Controller\Api\Person;

use App\Query\ListPersonsQuery;
use App\Query\ListPersonsQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    /**
     * @var ListPersonsQueryHandler
     */
    private $listPersonsQueryHandler;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        ListPersonsQueryHandler $listPersonsQueryHandler,
        SerializerInterface $serializer,
        RouterInterface $router
    ) {
        $this->listPersonsQueryHandler = $listPersonsQueryHandler;
        $this->serializer              = $serializer;
        $this->router                  = $router;
    }

    public function __invoke(Request $request)
    {
        $query       = new ListPersonsQuery();
        $query->page = $request->query->getInt('page', $query->page);
        $query->max  = $request->query->getInt('max', $query->max);

        $persons = ($this->listPersonsQueryHandler)($query);

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

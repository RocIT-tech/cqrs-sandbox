<?php

declare(strict_types=1);

namespace App\Tests\functional\Controller\Api\Challenger;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\functional\SchemaFactory;
use Generator;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \App\Controller\Api\Challenger\AddChallengers
 * @covers ::__construct
 */
class AddChallengersTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function failedChallengers(): Generator
    {
        yield 'missing rank' => [
            'tetrisGameId'       => 'tetris_game_empty',
            'payload'            => [
                [
                    'personId' => 'person_adrien_r',
                    'rank'     => null,
                ],
            ],
            'expectedStatusCode' => Response::HTTP_BAD_REQUEST,
        ];

        yield 'duplicate challenger + rank' => [
            'tetrisGameId'       => 'tetris_game_full',
            'payload'            => [
                [
                    'personId' => 'person_adrien_r',
                    'rank'     => 1,
                ],
            ],
            'expectedStatusCode' => Response::HTTP_CONFLICT,
        ];

        yield 'unknown challenger' => [
            'tetrisGameId'       => 'tetris_game_empty',
            'payload'            => [
                [
                    'personId' => 'should_not_be_found_challenger',
                    'rank'     => 1,
                ],
            ],
            'expectedStatusCode' => Response::HTTP_NOT_FOUND,
        ];

        yield 'unknown tetris game' => [
            'tetrisGameId'       => 'should_not_be_found_tetris_game',
            'payload'            => [
                [
                    'personId' => 'person_adrien_r',
                    'rank'     => 1,
                ],
            ],
            'expectedStatusCode' => Response::HTTP_NOT_FOUND,
        ];

        yield 'empty challengers list' => [
            'tetrisGameId'       => 'tetris_game_empty',
            'payload'            => [],
            'expectedStatusCode' => Response::HTTP_BAD_REQUEST,
        ];
    }

    /**
     * @test
     * @dataProvider failedChallengers
     * @covers ::__invoke
     */
    public function failedAddingNewChallengers(string $tetrisGameId, array $payload, int $expectedStatusCode): void
    {
        $client = static::createClient();

        $response = $client->request('POST', "/api/tetris-games/{$tetrisGameId}/challengers", [
            'json' => $payload,
        ]);

        $schema = SchemaFactory::createFromResponse(
            '/api/tetris-games/{tetrisGameId}/challengers',
            'POST',
            $response->getStatusCode()
        );

        self::assertResponseStatusCodeSame($expectedStatusCode);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertMatchesJsonSchema($schema);
    }

    public function succeededChallengers(): Generator
    {
        yield 'single challenger' => [
            'tetrisGameId'                => 'tetris_game_empty',
            'payload'                     => [
                [
                    'personId' => 'person_adrien_r',
                    'rank'     => 1,
                ],
            ],
            'expectedStatusCode'          => Response::HTTP_CREATED,
            'expectedNumberOfChallengers' => 1,
        ];

        yield 'two challengers' => [
            'tetrisGameId'                => 'tetris_game_empty',
            'payload'                     => [
                [
                    'personId' => 'person_mathieu_c',
                    'rank'     => 2,
                ],
                [
                    'personId' => 'person_remi_b',
                    'rank'     => 3,
                ],
            ],
            'expectedStatusCode'          => Response::HTTP_CREATED,
            'expectedNumberOfChallengers' => 2,
        ];
    }

    /**
     * @test
     * @dataProvider succeededChallengers
     * @covers ::__invoke
     */
    public function succeededAddingNewChallengers(
        string $tetrisGameId,
        array $payload,
        int $expectedStatusCode,
        int $expectedNumberOfChallengers
    ): void {
        $client = static::createClient();

        $response = $client->request('POST', "/api/tetris-games/{$tetrisGameId}/challengers", [
            'json' => $payload,
        ]);

        $schema = SchemaFactory::createFromResponse(
            '/api/tetris-games/{tetrisGameId}/challengers',
            'POST',
            $response->getStatusCode()
        );

        self::assertResponseStatusCodeSame($expectedStatusCode);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertMatchesJsonSchema($schema);
        self::assertCount($expectedNumberOfChallengers, $response->toArray(false));
    }

    public function succeededChallengersWithCustomId(): Generator
    {
        yield 'single challenger' => [
            'tetrisGameId'                => 'tetris_game_empty',
            'payload'                     => [
                [
                    'id'       => '#DUMMY_ID#',
                    'personId' => 'person_adrien_r',
                    'rank'     => 1,
                ],
            ],
            'expectedStatusCode'          => Response::HTTP_CREATED,
            'expectedNumberOfChallengers' => 1,
        ];

        yield 'two challengers' => [
            'tetrisGameId'                => 'tetris_game_empty',
            'payload'                     => [
                [
                    'id'       => '#DUMMY_ID_1#',
                    'personId' => 'person_mathieu_c',
                    'rank'     => 2,
                ],
                [
                    'id'       => '#DUMMY_ID_2#',
                    'personId' => 'person_remi_b',
                    'rank'     => 3,
                ],
            ],
            'expectedStatusCode'          => Response::HTTP_CREATED,
            'expectedNumberOfChallengers' => 2,
        ];
    }

    /**
     * @test
     * @dataProvider succeededChallengersWithCustomId
     * @covers ::__invoke
     */
    public function succeededAddingNewChallengersWithCustomId(
        string $tetrisGameId,
        array $payload,
        int $expectedStatusCode,
        int $expectedNumberOfChallengers
    ): void {
        $client = static::createClient();

        $response = $client->request('POST', "/api/tetris-games/{$tetrisGameId}/challengers", [
            'json' => $payload,
        ]);

        $schema = SchemaFactory::createFromResponse(
            '/api/tetris-games/{tetrisGameId}/challengers',
            'POST',
            $response->getStatusCode()
        );

        self::assertResponseStatusCodeSame($expectedStatusCode);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertMatchesJsonSchema($schema);
        self::assertCount($expectedNumberOfChallengers, $response->toArray(false));
    }
}

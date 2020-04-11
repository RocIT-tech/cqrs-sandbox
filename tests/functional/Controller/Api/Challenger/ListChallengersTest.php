<?php

declare(strict_types=1);

namespace App\Tests\functional\Controller\Api\Challenger;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\functional\SchemaFactory;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

/**
 * @coversDefaultClass \App\Controller\Api\Challenger\ListChallengers
 * @covers ::__construct
 */
class ListChallengersTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function tetrisGames()
    {
        yield 'full list' => [
            'tetris_game_full',
            3,
        ];

        yield 'empty list' => [
            'tetris_game_empty',
            0,
        ];
    }

    /**
     * @test
     * @dataProvider tetrisGames
     * @covers ::__invoke
     */
    public function retrieveListOfChallengers(string $tetrisGameId, int $expectedCountOfChallengers): void
    {
        $client = static::createClient();

        $response = $client->request('GET', "/api/tetris-games/{$tetrisGameId}/challengers");
        $schema   = SchemaFactory::createFromResponse(
            '/api/tetris-games/{tetrisGameId}/challengers',
            'GET',
            $response->getStatusCode()
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertMatchesJsonSchema($schema);
        self::assertCount($expectedCountOfChallengers, $response->toArray(false));
    }
}

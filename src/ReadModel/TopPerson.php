<?php

declare(strict_types=1);

namespace App\ReadModel;

class TopPerson
{
    public string $personId;

    public string $personName;

    public int $gameCount;

    public int $victoryCount;

    public ?int $averageRank;

    public bool $isWinner;
}

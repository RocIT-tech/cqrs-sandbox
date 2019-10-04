<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tetris_game")
 */
class TetrisGame
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="tetris_game_id", type="string", length=40, nullable=false)
     */
    public $id;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="date", type="date_immutable", nullable=false)
     */
    public $date;

    /**
     * @var Challenger[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Challenger",
     *     mappedBy="tetrisGame",
     *     orphanRemoval=true
     * )
     */
    public $challengers;
}

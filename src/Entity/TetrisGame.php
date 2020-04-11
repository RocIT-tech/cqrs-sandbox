<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tetris_game")
 * @ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class TetrisGame
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="tetris_game_id", type="string", length=40, nullable=false)
     */
    public string $id;

    /**
     * @var DateTimeImmutable
     *
     * @ORM\Column(name="date", type="date_immutable", nullable=false)
     */
    public DateTimeImmutable $date;

    /**
     * @var Challenger[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Challenger",
     *     mappedBy="tetrisGame",
     *     orphanRemoval=true
     * )
     */
    public iterable $challengers;

    public function __construct()
    {
        $this->challengers = new ArrayCollection();
    }
}

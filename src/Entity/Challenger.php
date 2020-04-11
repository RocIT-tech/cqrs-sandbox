<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="challenger",
 *     uniqueConstraints={
 *      @UniqueConstraint(
 *          name="challenger_tetris_person_rank_unique_index",
 *          columns={"tetris_game_id", "person_id", "rank"}
 *      )
 *     }
 * )
 * @ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Challenger
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="challenger_id", type="string", length=40, nullable=false)
     */
    public string $id;

    /**
     * @var TetrisGame
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\TetrisGame",
     *     inversedBy="challengers"
     * )
     * @ORM\JoinColumn(
     *     name="tetris_game_id",
     *     referencedColumnName="tetris_game_id",
     *     nullable=false
     * )
     */
    public TetrisGame $tetrisGame;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Person",
     *     inversedBy="playedTetrisGames"
     * )
     * @ORM\JoinColumn(
     *     name="person_id",
     *     referencedColumnName="person_id",
     *     nullable=false
     * )
     */
    public Person $person;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="smallint", nullable=false)
     */
    public int $rank;
}

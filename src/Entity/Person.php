<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ChangeTrackingPolicy;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person")
 * @ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Person
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(name="person_id", type="string", length=40, nullable=false)
     */
    public string $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    public string $name;

    /**
     * @var Challenger[]|Collection
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Challenger",
     *     mappedBy="person",
     *     orphanRemoval=true
     * )
     */
    public iterable $playedTetrisGames;

    public function __construct()
    {
        $this->playedTetrisGames = new ArrayCollection();
    }
}

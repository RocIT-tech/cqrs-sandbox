<?php

declare(strict_types=1);

namespace App\Command\CreateChallengersCommand;

use Symfony\Component\Validator\Constraints as Assert;

class Challenger
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Type("string")
     */
    public $id;

    /**
     * @var string
     *
     * @Assert\NotNull(groups={"form"})
     * @Assert\NotBlank(allowNull=true, groups={"form"})
     * @Assert\Type("string", groups={"form"})
     */
    public $personId;

    /**
     * @var int
     *
     * @Assert\NotNull(groups={"form"})
     * @Assert\NotBlank(allowNull=true, groups={"form"})
     * @Assert\Type("int", groups={"form"})
     * @Assert\Range(
     *     min=1,
     *     groups={"form"}
     * )
     */
    public $rank;
}

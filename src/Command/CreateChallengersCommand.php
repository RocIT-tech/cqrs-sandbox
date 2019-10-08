<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\CreateChallengersCommand\Challenger;
use Symfony\Component\Validator\Constraints as Assert;

class CreateChallengersCommand
{
    /**
     * @var string
     *
     * @Assert\NotNull(groups={"form"})
     * @Assert\NotBlank(allowNull=true, groups={"form"})
     * @Assert\Type("string", groups={"form"})
     */
    public $tetrisGameId;

    /**
     * @var Challenger[]
     *
     * @Assert\Count(min=1)
     */
    public $challengers;
}

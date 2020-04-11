<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\CreateChallengersCommand\Challenger;
use Symfony\Component\Validator\Constraints as Assert;

class CreateChallengersCommand
{
    /**
     * @Assert\NotNull(groups={"Default", "form"})
     * @Assert\NotBlank(allowNull=true, groups={"Default", "form"})
     * @Assert\Type("string", groups={"Default", "form"})
     */
    public string $tetrisGameId;

    /**
     * @var Challenger[]
     *
     * @Assert\Valid(groups={"Default", "form"})
     * @Assert\Count(min=1)
     */
    public array $challengers;
}

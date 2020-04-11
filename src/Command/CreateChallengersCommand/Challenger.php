<?php

declare(strict_types=1);

namespace App\Command\CreateChallengersCommand;

use Symfony\Component\Validator\Constraints as Assert;

class Challenger
{
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     * @Assert\Type("string")
     */
    public ?string $id = null;

    /**
     * @Assert\NotNull(groups={"Default", "form"})
     * @Assert\NotBlank(allowNull=true, groups={"Default", "form"})
     * @Assert\Type("string", groups={"Default", "form"})
     */
    public ?string $personId;

    /**
     * @Assert\NotNull(groups={"Default", "form"})
     * @Assert\NotBlank(allowNull=true, groups={"Default", "form"})
     * @Assert\Type("int", groups={"Default", "form"})
     * @Assert\Range(
     *     min=1,
     *     groups={"Default", "form"}
     * )
     */
    public ?int $rank;
}

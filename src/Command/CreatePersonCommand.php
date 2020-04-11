<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePersonCommand
{
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     */
    public string $id;

    /**
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     */
    public string $name;
}

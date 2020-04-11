<?php

declare(strict_types=1);

namespace App\Command;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTetrisGameCommand
{
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     */
    public string $id;

    /**
     * @Assert\Type("DateTimeImmutable")
     */
    public DateTimeImmutable $date;
}

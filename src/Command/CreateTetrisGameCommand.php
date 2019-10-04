<?php

declare(strict_types=1);

namespace App\Command;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTetrisGameCommand
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     */
    public $id;

    /**
     * @var DateTimeImmutable
     *
     * @Assert\Type("DateTimeImmutable")
     */
    public $date;
}

<?php

declare(strict_types=1);

namespace App\Query;

use Symfony\Component\Validator\Constraints as Assert;

class ListTetrisGameChallengersQuery
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\NotBlank(allowNull=true)
     */
    public $tetrisGameId;
}

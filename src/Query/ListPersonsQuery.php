<?php

declare(strict_types=1);

namespace App\Query;

use Symfony\Component\Validator\Constraints as Assert;

class ListPersonsQuery
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $max = 10;
}

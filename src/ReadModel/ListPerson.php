<?php

declare(strict_types=1);

namespace App\ReadModel;

class ListPerson
{
    /**
     * @var int
     */
    public $page;

    /**
     * @var int
     */
    public $count;

    /**
     * @var Person[]
     */
    public $persons;

    /**
     * @var Link[]
     */
    public $_links = [];
}

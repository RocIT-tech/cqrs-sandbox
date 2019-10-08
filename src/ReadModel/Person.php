<?php

declare(strict_types=1);

namespace App\ReadModel;

class Person
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Link[]
     */
    public $_links = [];
}

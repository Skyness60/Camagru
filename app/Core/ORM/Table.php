<?php
// app/Core/ORM/Table.php

namespace App\Core\ORM;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Table
{
    public function __construct(public string $name) {}
}

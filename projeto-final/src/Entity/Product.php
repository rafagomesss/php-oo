<?php
namespace Code\Entity;

use Code\DB\Entity;

class Product extends Entity
{
    protected $table = 'products';
    public static $filters = [
        'name' => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'content' => FILTER_SANITIZE_STRING,
        'price' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_THOUSAND],
        'is_active' => FILTER_SANITIZE_STRING
    ];
}
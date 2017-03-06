<?php namespace App\Controllers;

use Slim\Container;

class Controller
{
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($var)
    {
        return $this->container->{$var};
    }
}
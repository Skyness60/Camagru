<?php

namespace App\Controller;

class HomeController extends BaseController
{
    public function __construct(\App\Config\Container $container)
    {
        parent::__construct($container);
    }

    public function show()
    {
        $this->render('home');
    }
}

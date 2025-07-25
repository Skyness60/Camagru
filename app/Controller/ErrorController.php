<?php

namespace App\Controller;

class ErrorController extends BaseController
{
    public function __construct(\App\Config\Container $container)
    {
        parent::__construct($container);
    }

    public function notFound(): void
    {
        http_response_code(response_code: 404);
        $this->render('404');
    }
}
<?php
namespace App\Controller;

use App\Config\Container;
use App\Service\Request;

abstract class BaseController
{
    protected Container $container;
    protected Request $request;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->request = $container->get(Request::class);
    }

    protected function render(string $view, array $params = []): void
    {
        extract($params);
        require __DIR__ . '/../View/' . $view . '.php';
    }
}

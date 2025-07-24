<?php

namespace App\Controller;

class IndexController
{
    public function show()
    {
        // Render the index view
        require_once __DIR__ . '/../View/index.php';
    }
}

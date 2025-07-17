<?php
// src/Core/App.php
namespace App\Core;

use App\Config\EnvLoader;

require_once __DIR__ . '/../Config/EnvLoader.php';
EnvLoader::load();

class App
{
    public function run()
    {
        echo "Application is running!";
    }
}

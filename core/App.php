<?php

require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Request.php';
require_once BASE_PATH . '/core/Response.php';

// Auto-load controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/app/Controllers/' . $class . '.php',
        BASE_PATH . '/app/Models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

class App
{
    protected Router $router;

    public function __construct()
    {
        Session::start();
        $this->router = new Router();
        $router = $this->router;
        require BASE_PATH . '/config/routes.php';
    }

    public function run(): void
    {
        $this->router->dispatch();
    }
}

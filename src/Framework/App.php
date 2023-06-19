<?php

namespace App\Framework;

use App\Service\Router;

class App
{
    public function run($action)
    {
        list($controllerName, $actionName) = Router::resolveRoute($action);

        return $controllerName::$actionName();
    }
}

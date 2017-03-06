<?php

require __DIR__ . '/../../vendor/autoload.php';
session_start();

// Instantiate the app
$config = require __DIR__ . '/../Config/Config.php';
$app = new \Slim\App([
    'settings' => $config
]);

require __DIR__ . '/Helpers.php';

// Set up dependencies
$Dependencies = require __DIR__ . '/Dependencies.php';
if( sizeof($Dependencies) > 0 ) {
    $container = $app->getContainer();
    foreach ($Dependencies as $key => $dependency) {
        $container[$key] = $dependency;
    }
}

// Register middleware
require __DIR__ . '/../Middleware/Kernel.php';

use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection(config('db'));

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

// Register routes
$routes = require __DIR__ . '/../Config/Routes.php';

return $app;

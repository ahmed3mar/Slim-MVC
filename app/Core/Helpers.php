<?php

if (!function_exists('url')) {
    function url($path) {
        return request()->getUri()->withPath($path);
    }
}

/**
 * @return mixed|\Slim\App
 */
function app() {
    global $app;
    return $app;
}

function config_arr($data, $name) {
    $name_exp = explode('.', $name, 2);
    $isNested = sizeof($name_exp) === 2;
    if( $isNested ) {
        $new_data = $data[$name_exp[0]];
        return config_arr($new_data, $name_exp[1]);
    }
    return $data[$name];
}

function config($name) {
    $name_exp = explode('.', $name, 2);
    $isNested = sizeof($name_exp) === 2;
    $name = $isNested ? $name_exp[0] : $name;

    $data = container()->get('settings')[$name];
    if( $isNested ) {
        return config_arr($data, $name_exp[1]);
    }

    return $data;
}

/**
 * @return \Interop\Container\ContainerInterface
 */
function container() {
    return app()->getContainer();
}

/**
 * @return \Slim\Http\Request
 */
function request() {
    return container()->request;
}

/**
 * @return \Slim\Http\Response
 */
function response() {
    return container()->response;
}

function redirect($url, $code = 302) {
    return response()->withRedirect($url, $code);
}

/**
 *
 * @return \Slim\Interfaces\RouterInterface
 */
function router() {
    return container()->get('router');
}

/**
 * Class Route
 * @method static \Slim\Interfaces\RouteInterface get($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface post($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface put($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface patch($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface delete($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface options($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface any($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface group($name, $arguments)
 * @method static \Slim\Interfaces\RouteInterface map($name, $arguments)
 */
class Route {
    public static function __callStatic($name, $arguments)
    {
        $pattern = $arguments[0];
        $callable = $arguments[1];
        if (gettype($callable) === 'string') {
            $callable = str_replace("@", ":", $callable);
            if( !preg_match('@App\\Controllers@i', $callable) ) $callable = 'App\Controllers\\' . $callable;
            return app()->{$name}($pattern, $callable);
        }
        return app()->{$name}(...$arguments);
    }
}

///**
// * @param $method
// * @param $pattern
// * @param $callable
// * @return \Slim\Interfaces\RouteInterface
// */
//function setRoute($method, $pattern, $callable) {
//    $method = is_array($method) ? $method : [strtoupper($method)];
//    return app()->map($method, $pattern, $callable);
//}

/**
 * @param $name
 * @param array $data
 * @param array $queryParams
 * @internal param array $args
 * @return string
 */
function route($name, array $data = [], array $queryParams = []) {
    return router()->pathFor($name, $data, $queryParams);
}

/**
 * @param  mixed  $data   The data
 * @param  int    $status The HTTP status code.
 * @param  int    $encodingOptions Json encoding options
 * @throws \RuntimeException
 * @return \Slim\Http\Response
 */
function json($data, $status = null, $encodingOptions = 0) {
    return response()->withJson($data, $status, $encodingOptions);
}

function view($view, $vars = []) {
    return container()->view->render(response(), $view . '.twig', $vars);
}

if (!function_exists('dd')) {
    function dd($arr) {
        var_dump($arr);
        exit;
    }
}

function pri($arr, $exit = true) {
    echo "<pre>";
    echo "-------------\n";
    print_r($arr);
    echo "\n-------------\n";
    echo "</pre>";
    if( $exit ) exit;
}
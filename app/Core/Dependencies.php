<?php

return [
    'view' => function (\Slim\Container $c) {
        $view = new \Slim\Views\Twig( config('view.templates'), config('view.config'));

        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

        $functions = config('view.functions');
        foreach ($functions as $function) {
            $view->getEnvironment()->addFunction(new Twig_SimpleFunction($function, $function));
        }

        return $view;
    },

    'logger' => function (\Slim\Container $c) {
        $settings = $c->get('settings')['logger'];
        $logger = new Monolog\Logger($settings['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    },

];
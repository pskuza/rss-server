<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use pskuza\rss\server;

try {

    $rss = new server("rss.db");

    $dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/', ['posts', 'getAll']);
    }, [
        'cacheFile' => __DIR__ . '/route.cache'
    ]);

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];

    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $rss->error(404, '404');
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $rss->error(405, '405');
            break;
        case FastRoute\Dispatcher::FOUND:
            $class = $routeInfo[1][0];
            $handler = $routeInfo[1][1];
            $options = (array) $routeInfo[2];
            try {
                $return = $api->$class->$handler($options);
            } catch (\InvalidArgumentException $e) {
                $rss->error(400, 'Invalid Argument');
            }
            if ($return[0] === true) {
                $rss->success($return[1], $return[2]);
            }
            $rss->error($return[1], $return[2]);
            break;
    }
} catch (\Exception $e) {
    http_response_code(500);
    error_log("Uncatched exception." . $e->getMessage(), 0);
}
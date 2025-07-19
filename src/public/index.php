<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

require_once __DIR__ . '/../config/env.php';

loadEnv(__DIR__ . '/../.env');

require_once __DIR__ . '/../config/bootstrap.php';
$routes = require __DIR__ . '/../Routes.php';

$context = new RequestContext();
$context->fromRequest(Request::createFromGlobals());

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->match($context->getPathInfo());
    if (!in_array($context->getMethod(), (array) ($parameters['_route_params']['_method'] ?? ['GET']))) {
        throw new MethodNotAllowedException([$context->getMethod()]);
    }
    $response = $parameters['_controller']();
    echo $response;
} catch (MethodNotAllowedException $e) {
    echo "Method Not Allowed";
    exit;
} catch (ResourceNotFoundException $e) {
    http_response_code(404);
    return '404 Not Found';
}
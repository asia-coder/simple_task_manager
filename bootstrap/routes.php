<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 14:23
 */

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider as Service;
use Task\Controllers\IndexController;
use Task\Controllers\LoginController;
use Task\Controllers\TaskController;

$route = new Klein\Klein();

$route->respond('GET', '/', function(Request $request, Response $response, Service $service) {
    return (new IndexController)->index($request, $response, $service);
});

$route->respond('POST', '/task_add', function(Request $request, Response $response, Service $service) {
    return (new TaskController)->add($request, $response, $service);
});

$route->respond('GET', '/login', function(Request $request, Response $response, Service $service) {
    return (new LoginController)->index($request, $response, $service);
});

$route->respond('POST', '/auth', function(Request $request, Response $response, Service $service) {
    return (new LoginController)->login($request, $response, $service);
});

$route->respond('GET', '/logout', function(Request $request, Response $response, Service $service) {
    return (new LoginController)->logout($request, $response, $service);
});

$route->respond('GET', '/edit_task', function(Request $request, Response $response, Service $service) {
    return (new TaskController)->editTask($request, $response, $service);
});

$route->respond('POST', '/edit_task', function(Request $request, Response $response, Service $service) {
    return (new TaskController)->edit($request, $response, $service);
});

$route->dispatch();
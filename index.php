<?php
/**
 * Created by PhpStorm.
 * User: mdressler
 * Date: 17.05.2018
 * Time: 00:27
 */

spl_autoload_register(function ($classname) {
    if (substr($classname, 0, 4) !== 'Mvc\\') {
        return;
    }

    $filename = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, substr($classname, 4)).'.php';

    if (file_exists($filename)) {
        include $filename;
    }
});

// get the requested url
$url = (isset($_GET['_url']) ? $_GET['_url'] : '');
$urlParts = explode('/', $url);

// build the controller class
$controllerName = (isset($urlParts[0]) && $urlParts[0] ? $urlParts[0] : 'index');
$controllerClassName = '\\Mvc\\Controller\\'.ucfirst($controllerName).'Controller';

// build the action method
$actionName = (isset($urlParts[1]) && $urlParts[1] ? $urlParts[1] : 'index');
$actionMethodName = ucfirst($actionName).'Action';

try {
    if (!class_exists($controllerClassName)) {
        throw new \Mvc\Library\NotFoundException();
    }

    $controller = new $controllerClassName;

    if (!$controller instanceof \Mvc\Controller\Controller || !method_exists($controller, $actionMethodName)) {
        throw new \Mvc\Library\NotFoundException();
    }

    $controller->$actionMethodName();
} catch (\Mvc\Library\NotFoundException $e) {
    http_response_code(404);
    echo 'Page not found: '.$controllerClassName.'::'.$actionMethodName;
} catch (\Exception $e) {
    http_response_code(500);
    echo 'Exception: '.$e->getMessage().' '.$e->getTraceAsString();
}
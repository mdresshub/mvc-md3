<?php
/**
 * Created by PhpStorm.
 * User: mdressler
 * Date: 18.05.2018
 * Time: 22:49
 */

namespace Mvc\Library;


class View
{
    protected $path, $controller, $action;

    public function __construct($path, $controllerName, $actionName)
    {
        $this->path = $path;
        $this->controller = $controllerName;
        $this->action = $actionName;
    }

    public function render() {
        $filename = $this->path.DIRECTORY_SEPARATOR.$this->controller.DIRECTORY_SEPARATOR.$this->action.'.phtml';

        if (!file_exists($filename)) {
            throw new NotFoundException();
        }

        include $filename;
    }
}
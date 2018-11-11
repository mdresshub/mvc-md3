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
    protected $path, $controller, $action, $vars = [];

    /**
     * @param string $path Basepath of the views
     * @param string $controllerName Current Controller
     * @param string $actionName Current Action
     */
    public function __construct($path, $controllerName, $actionName)
    {
        $this->path = $path;
        $this->controller = $controllerName;
        $this->action = $actionName;
    }

    /**
     * Set the view vars. The keys will be added to existing keys.
     *
     * @param array $vars
     */
    public function setVars(array $vars)
    {
        foreach ($vars as $key => $val) {
            $this->vars[$key] = $val;
        }
    }

    /**
     * Render the view
     *
     * @throws NotFoundException
     */
    public function render()
    {
        $filename = $this->path.DIRECTORY_SEPARATOR.$this->controller.DIRECTORY_SEPARATOR.$this->action.'.phtml';

        if (!file_exists($filename)) {
            throw new NotFoundException();
        }

        // spare the view the bloat of using "$this->vars[]" for every variable
        foreach ($this->vars as $key => $val) {
            $$key = $val;
        }

        include $filename;
    }
}
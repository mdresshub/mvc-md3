<?php
/**
 * Created by PhpStorm.
 * User: mdressler
 * Date: 18.05.2018
 * Time: 22:10
 */

namespace Mvc\Controller;


class IndexController implements Controller
{
    /** @var \Mvc\Library\View */
    protected $view;

    public function setView(\Mvc\Library\View $view)
    {
        $this->view = $view;
    }

    public function indexAction()
    {
        $this->view->setVars([
            'name' => 'Michael'
        ]);
    }
}
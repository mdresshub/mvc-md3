<?php
/**
 * Created by PhpStorm.
 * User: mdressler
 * Date: 20.05.2018
 * Time: 14:31
 */

namespace Mvc\Model;


class User extends ModelBase
{
    public $id, $name, $created;

    public function getSource()
    {
        return 'users';
    }

    public function beforeCreate()
    {
        $this->created = date('Y-m-d H:i:s');
    }
}
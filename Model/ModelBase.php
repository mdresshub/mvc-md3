<?php
/**
 * Created by PhpStorm.
 * User: mdressler
 * Date: 20.05.2018
 * Time: 14:01
 */

namespace Mvc\Model;


abstract class ModelBase
{
    private static $pdo;

    public function getPdo()
    {
        if (self::$pdo === null) {
            self::$pdo = new \PDO('mysql:host=127.0.0.1;dbname=mvc-md3', 'root', 'metaweb');
        }

        return self::$pdo;
    }

    public static function findFirst($options)
    {
        $model = new static();
        $table = $model->getSource();
        /** @var \PDO $pdo */
        $pdo = $model->getPdo();

        if (is_int($options)) {
            // we are looking for an id
            $stmt = $pdo->prepare('SELECT * FROM `'.$table.'` WHERE id = ? LIMIT 1');
            $stmt->execute([$options]);
        } elseif (is_array($options) && isset($options['criteria'])) {
            $stmt = $pdo->prepare('SELECT * FROM `'.$table.'` WHERE '.$options['criteria'].' LIMIT 1');
            $stmt->execute($options['bind']);
        } else {
            throw new \UnexpectedValueException('You need to specify the criteria');
        }

        return $stmt->fetchObject(get_class($model));
    }

    public static function find(array $options)
    {
        $model = new static();
        $table = $model->getSource();
        /** @var \PDO $pdo */
        $pdo = $model->getPdo();

        if (!isset($options['criteria'])) {
            throw new \UnexpectedValueException('You need to specify the criteria');
        }

        $stmt = $pdo->prepare('SELECT * FROM `'.$table.'` WHERE '.$options['criteria']);
        $stmt->execute($options['bind']);

        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class($model));
    }

    public function save()
    {
        $table = $this->getSource();
        /** @var \PDO $pdo */
        $pdo = $this->getPdo();

        $fields = [];
        foreach ($this as $name => $val) {
            if ($val === null) {
                $fields[] = "´$name´=null";
            } elseif (is_int($val)) {
                $fields[] = "´$name´=".$val;
            } else {
                $fields[] = "´$name´=".$pdo->quote($val);
            }
        }

        if ($this->id === null) {
            // new entry
            if (method_exists($this, 'beforeCreate')) {
                $this->beforeCreate();
            }
            if (!$pdo->exec('INSERT INTO ´'.$table.'´ SET '.implode(',', $fields))) {
                throw new \RuntimeException('Could not create '.get_class($this).': '.$pdo->errorInfo()[2]);
            }
            // fill the id
            $this->id = $pdo->lastInsertId();
        } else {
            // update entry
            if (method_exists($this, 'beforeUpdate')) {
                $this->beforeUpdate();
            }
            if ($pdo->exec('UPDATE ´'.$table.'´ SET '.implode(',', $fields).' WHERE ´id´ = '.((int)$this->id)) === FALSE) {
                throw new \RuntimeException('Could not update '.get_class($this).': '.$pdo->errorInfo()[2]);
            }
        }
    }

    abstract public function getSource();
}
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

    abstract public function getSource();
}
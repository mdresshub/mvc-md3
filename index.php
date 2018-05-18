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
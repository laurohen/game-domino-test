<?php

spl_autoload_register(function ($class) {
    $file = str_replace(['\\', 'Domino'], [DIRECTORY_SEPARATOR, 'src'], $class) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});
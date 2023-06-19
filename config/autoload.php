<?php
spl_autoload_register(function ($className) {
    $file = str_replace('App\\', 'src\\', $className);
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $file) . '.php';
    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $file;
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});
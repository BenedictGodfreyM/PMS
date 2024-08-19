<?php

spl_autoload_register('autoloader');

function autoloader($classname) {

    $extension = ".php";
    $filename = $classname . $extension;

    require_once $filename;

}

?>

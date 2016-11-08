<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 07.11.16
 * Time: 21:47
 */

spl_autoload_register(function ($class_name) {
    include "$class_name.php";
});
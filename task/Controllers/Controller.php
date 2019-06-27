<?php
/**
 * Created by PhpStorm.
 * User: dilshod
 * Date: 2019-05-12
 * Time: 14:43
 */

namespace Task\Controllers;


class Controller
{

    public function getTemplatePath(string $path = 'layout')
    {
        return APP_DIR . '/task/Views/' . $path . '.phtml';
    }
}
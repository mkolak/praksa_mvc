<?php

namespace App;

require_once '../vendor/autoload.php';

class View
{
    public static function loadTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');

        $twig = new \Twig\Environment($loader);

        $filter = new \Twig\TwigFilter('snake_case', function ($str) {
            return implode('_', explode(' ', strtolower($str)));
        });

        $twig->addFilter($filter);
        return $twig;
    }

    public static function redirect($path)
    {
        $url = "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
        header('Location: ' . $url . $path);
        die();
    }
}

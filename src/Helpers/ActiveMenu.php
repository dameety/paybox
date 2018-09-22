<?php

class ActiveMenu
{
    public static function isActiveRoute($route, $output = 'uk-active uk-text-bold')
    {
        if (Route::currentRouteName() === $route) {
            return $output;
        }
    }

    public static function areActiveRoutes(Array $routes, $output = 'uk-active uk-text-bold')
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() === $route) {
                return $output;
            }
        }
    }
}
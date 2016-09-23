<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 16/9/20
 * Time: 17:30
 */

/**
 * @param string $name
 * @return string
 */
function route_uri($name)
{
    return app('router')->getRoutes()->getByName($name)->getUri();
}

/**
 * @param $name
 * @return string
 */
function cas_route_uri($name)
{
    $name = config('cas.router.name_prefix').$name;

    return route_uri($name);
}

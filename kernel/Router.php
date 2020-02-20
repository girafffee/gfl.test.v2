<?php

$routes = array();

function addRoute($url, $controller, $callbacks = '')
{
    global $routes;

    $routes[$url] = [
        'url' => $url,
        'controller' => $controller
    ];
    setAction($routes[$url]);

    if(is_array($callbacks))
        foreach ($callbacks as $callback => $params)
        {
            if(function_exists($callback))
                $callback($routes[$url], $params);
        }

    return $routes[$url];
}

function addGroup ($url, $controller, $callbacks = ''){
    global $routes;

    $url = strtolower($url);
    $url = str_replace('{', '', $url);
    $url = str_replace('}', '', $url);
    $url = explode('/', $url);


    array_shift($url);
    $mainUrl = '/'.$url[0];

    $path = array();

    for($i = 1; $i < sizeof($url); $i++){
        $path[] = $url[$i];
    }

    addRoute($mainUrl, $controller, $callbacks);
    addPath($routes[$mainUrl], $path);
}

function addPath(&$route, $path_array)
{
    $route['path'] = $path_array;
}

function setNameRoute(&$route, $param)
{
    $route = array_merge($route, ['name' => $param]);
}

function setAction(&$route)
{
    $methods = explode('#', $route['controller']);
    $route['controller'] = $methods[0];

    if(count($methods) == 2)
    {
        $route['action'] = $methods[1];
    }
    else
    {
        $route['action'] = DEFAULT_ACTION;
    }
}

function callController()
{
    global $routes;

    $url =  explode('?' , $_SERVER['REQUEST_URI']);
    $url = $url[0];


    if(array_key_exists($url, $routes) OR array_key_exists(substr($url, 0, strlen($url) - 1) , $routes))
    {
        // статический маршрут
        if(!isset($routes[$url]))
            $url = substr($url, 0, strlen($url) - 1);

        return callActionBy($routes[$url]);
    }
    else
    {
        // динамический маршрут с аргументами
        $url = explode("/", $url);
        array_shift($url);
        $mainUrl = '/'.$url[0];

        if(isset($routes[$mainUrl]))
        {
            array_shift($url);
            $routes[$mainUrl]['params'] = $url;

            if(checkArgsRoute($mainUrl))
            {
                $action = $routes[$mainUrl]['action'];

                if(in_array('action', $routes[$mainUrl]['path']))
                {
                    $action_key = array_search('action', $routes[$mainUrl]['path']);

                    $routes[$mainUrl]['action'] = $routes[$mainUrl]['params'][$action_key];
                    unset($routes[$mainUrl]['params'][$action_key]);
                }

                return callActionBy($routes[$mainUrl]);
            }
        }
    }

    return renderError('404');
}
function callActionBy($route)
{
    $object = $route['controller'];
    if(file_exists(controllerFile($object)))
    {
        require $object;

        $action = buildActionName($route);
        $params = array_key_exists('params', $route) ? $route['params'] : array();

        if($action && function_exists($action))
        {
            // сохраняем или проверяем админа в сессии, если контроллер определен
            // в функции BaseController::checkAccessControllers
            if(setOrCheckAdmin($route['controller']))
                return $action($params);
            else
                return renderError('403');

        }
    }
    return renderError('404');
}

function checkArgsRoute($name)
{
    global $routes;

    if(array_key_exists('params', $routes[$name]) && array_key_exists('path', $routes[$name]))
        return (count($routes[$name]['params']) == count($routes[$name]['path']));
}

function route($name, $params = array())
{
    global $routes;

    if(array_key_exists($name, $routes))
        return $routes[$name]['url'];

    foreach ($routes as $route)
    {
        if(array_key_exists('name', $route) && $route['name'] == $name)
        {
            if(empty($params))
                return $route['url'];

            if(is_array($params))
            {
                $route['params'] = $params;
                return buildUrl($route);
            }

        }
    }
    return $routes['/']['url'];

}

function buildUrl($route)
{
    return $route['url'] . '/' . implode('/', $route['params']);
}

function buildActionName($route)
{
    if(array_key_exists('action', $route))
    {
        $action = ACTION_PREFIX . ucfirst($route['action']);
        return $action;
    }
    return false;
}

function controllerFile(&$object)
{
    $object = PATH_TO_CONTROLLERS . $object . '.php';
    return $object;
}

require_once 'web.php';

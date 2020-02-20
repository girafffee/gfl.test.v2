<?php

$content = '';

function render($tplName, $data = array())
{
    if(empty($tplName) || $tplName == '')
        return NULL;

    if(!empty($data))
        extract($data);

    // включаем буфер
    ob_start();

    include getPathTpl($tplName);

    // сохраняем всё что есть в буфере в переменную $content
    $content = ob_get_contents();

    // отключаем и очищаем буфер
    ob_end_clean();

    return $content;
}

function renderError($number)
{
    // включаем буфер
    ob_start();

    $index = PATH_TO_TEMPLATES_ERRORS . $number . '/index.php';
    if(file_exists($index))
        require $index;

    $content = ob_get_contents();

    // отключаем и очищаем буфер
    ob_end_clean();

    return $content;
}

function checkAccessControllers()
{
    return [
        'AdminController',
        'AjaxAdminController'
    ];
}


function includeControllers()
{
    if($controllers = opendir(PATH_TO_LAY_CONTROLLERS) )
    {
        while (false !== ($entry = readdir($controllers))) {
            if ($entry != "." && $entry != "..") {
                include PATH_TO_LAY_CONTROLLERS . $entry;
            }
        }
    }
}

function filterData(&$data)
{
    foreach ($_POST as $key => $post)
    {
        if(is_string($post) && $post != '')
        {
            $data[$key] = htmlspecialchars(trim($post));
            continue;
        }

        if(is_array($post))
            $data[$key] = $post;
    }
}

function getPathTpl($tplName)
{
    $path = PATH_TO_TEMPLATES . $tplName;

    if(file_exists($path))
        return $path;
}

function renderAjax($tplPath, $params, $result = true)
{
    $content = [
        'html'      => render ($tplPath, $params),
        'success'   => $result
    ];

    echo json_encode($content, JSON_UNESCAPED_UNICODE);
    die;
}

function returnAjaxResult($result)
{
    $content = ['success' => $result];

    echo json_encode($content, JSON_UNESCAPED_UNICODE);
    die;
}

function redirect($nameRoute, $args = array())
{
    $url = route($nameRoute, $args);
    header("Location: " . SITE_URL . $url);
    exit;
}

function setOrCheckAdmin($controller)
{
    if(in_array($controller, checkAccessControllers()))
    {
        if(!setAdmin())
            return checkAdmin();
    }
    return true;
}

function setAdmin()
{
    if(!array_key_exists('is_active_admin', $_SESSION)
        && array_key_exists('PHP_AUTH_USER', $_SERVER)
        && $_SERVER['PHP_AUTH_USER'] == ADMIN_LOGIN)
    {
        $_SESSION['is_active_admin'] = true;

        return true;
    }
    return false;
}

function checkAdmin()
{
    return array_key_exists('is_active_admin', $_SESSION) && (bool)$_SESSION['is_active_admin'];
}

includeControllers();
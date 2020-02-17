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

includeControllers();
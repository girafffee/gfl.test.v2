<?php

function actionIndex()
{
    return render('admin/all.tpl.php');
}

function actionView($params)
{
    $action = 'view' . ucfirst($params[0]);

    if(function_exists($action))
    {
        return $action();
    }
    else
    {
        return actionIndex();
    }
}

function viewBooks()
{
    echo 'viewBooks'; die;
}

function viewAuthors()
{
    echo 'viewAuthors'; die;
}

function viewGenres()
{
    echo 'viewGenres'; die;
}
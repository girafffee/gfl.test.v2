<?php

function actionAjaxDeleteBook()
{
    $data = array();
    filterData($data);

    actionModel('Books', 'safeDeleteBook', $data);
    $books = Get('Books', 'getAllBooks');

    renderAjax('ajax/admin.books.tpl.php', [
        'books' => $books
    ]);
}

function actionAjaxRetrieveBook()
{
    $data = array();
    filterData($data);

    actionModel('Books', 'safeRetrieveBook', $data);
    $books = Get('Books', 'getAllBooks');

    renderAjax('ajax/admin.books.tpl.php', [
        'books' => $books
    ]);
}

function actionAjaxDeleteAuthor()
{
    $data = array();
    filterData($data);

    actionModel('Authors', 'Delete', $data);
    $authors = Get('Authors');

    renderAjax('ajax/authors.tpl.php', [
        'authors' => $authors
    ]);
}

function actionAjaxCreateAuthors()
{
    $data = array();
    filterdata($data);

    actionModel('Authors', 'Create', $data);
    $authors = Get('Authors');

    renderAjax('ajax/authors.tpl.php', [
        'authors' => $authors
    ]);
}

function actionAjaxUpdateAuthors()
{
    $data = array();
    filterdata($data);

    actionModel('Authors', 'Update', $data);
    $authors = Get('Authors');

    renderAjax('ajax/authors.tpl.php', [
        'authors' => $authors
    ]);
}


function actionAjaxDeleteGenre()
{
    $data = array();
    filterData($data);

    actionModel('Genres', 'Delete', $data);
    $genres = Get('Genres');

    renderAjax('ajax/genres.tpl.php', [
        'genres' => $genres
    ]);
}

function actionAjaxCreateGenres()
{
    $data = array();
    filterdata($data);

    actionModel('Genres', 'Create', $data);
    $genres = Get('Genres');

    renderAjax('ajax/genres.tpl.php', [
        'genres' => $genres
    ]);
}

function actionAjaxUpdateGenres()
{
    $data = array();
    filterdata($data);

    actionModel('Genres', 'Update', $data);
    $genres = Get('Genres');

    renderAjax('ajax/genres.tpl.php', [
        'genres' => $genres
    ]);
}

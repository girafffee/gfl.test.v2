<?php

function actionIndex()
{
    return render('admin/all.tpl.php');
}

function actionEdit($params)
{
    if(!isset($params[2]) || $params[2] == '')
    {
        /** выводим страницу ошибки, но пока отсылаем на главную :) */
        return actionIndex();
    }
    $id = $params[2];

    if(!empty($_POST))
        actionModel('Books', 'saveBook', $id);

    $book = Get('Books', 'getBookSingleEdit', [
        'id' => $id
    ]);
    $book = array_shift($book);

    foreach (['genres', 'authors'] as $obj)
        $book[$obj] = array_key_exists($obj, $book)
            ? explode(',', $book[$obj])
            : array();

    $genres = Get('Genres');
    $authors = Get('Authors');

    return render('admin/book.edit.tpl.php', [
        'books' => $book,
        'genres' => $genres,
        'authors' => $authors
    ]);
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

function actionCreateBook()
{
    if(!empty($_POST))
        actionModel('Books', 'createBook');

    $genres = Get('Genres');
    $authors = Get('Authors');

    return render ('admin/book.create.tpl.php', [
        'genres'    => $genres,
        'authors'   => $authors,
    ]);

}

function viewBooks()
{
    $books = Get('Books', 'getAllBooks');

    return render ('admin/books.tpl.php', [
        'books' => $books
    ]);
}

function viewAuthors()
{
    $authors = Get('Authors');

    return render ('admin/authors.tpl.php', [
        'authors' => $authors
    ]);
}

function viewGenres()
{
    $genres = Get('Genres');

    return render ('admin/genres.tpl.php', [
        'genres' => $genres
    ]);
}
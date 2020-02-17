<?php

function actionIndex()
{
    $books = Get('Catalog', 'getBooksCatalog');

    $genres = All(['table' => 'genres']);
    $authors = All(['table' => 'authors']);

    return render('catalog/all.tpl.php', [
        'authors' => $authors,
        'genres' => $genres,
        'books' => $books
    ]);
}

function actionView($params)
{
    $id = array_shift($params);

    $book = Get('Catalog', 'getBookSingle', [
        'id' => $id
    ]);

    return render('catalog/single.tpl.php', [
        'book' => $book[0]
    ]);

}
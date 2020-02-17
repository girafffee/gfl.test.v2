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

function actionCreateBook()
{
    if(!empty($_POST))
        actionModel('Books', 'createBook');

    $genres = Get('Genres', 'getAllGenres');
    $authors = Get('Authors', 'getAllAuthors');

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
    $authors = Get('Authors', 'getAllAuthors');

    return render ('admin/authors.tpl.php', [
        'authors' => $authors
    ]);
}

function viewGenres()
{
    $genres = Get('Genres', 'getAllGenres');

    return render ('admin/genres.tpl.php', [
        'genres' => $genres
    ]);
}
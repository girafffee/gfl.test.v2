<?php

function Books()
{
    global $query;

    buildQuery(array_merge([
        'table' => 'books b',
        'orderBy' => 'b.title'
    ], $query));
}

function getAllBooks()
{
    global $query;

    $query['select'] = [
        'id'            => 'b.id',
        'title'         => 'b.title',
        'created_at'    => 'b.created_at',
        'status'        => 'b.status'
    ];
}

function createBook()
{
    $data = array();
    filterData($data);

    if(isset($data['upd']))
    {
        $genres_authors = $data['upd'];
        unset($data['upd']);
    }

    /** TODO:
     *  Вызвать метод Create, тем самым создать книгу
     *  получить последний сохраненный id из таблицы books
     *  и использовать его в качестве book_id для записей в
     *  <book_genres> & <book_authors>
     */
}

<?php

function Books($position = true)
{
    mainFuncModel([
        'table' => 'books b',
        'orderBy' => 'b.title',
        'addFields' => ['title', 'desc_short', 'desc_full'],
        'findField' => 'id',

        'deleteByFields' => [
            'status' => BOOK_STATUS_DELETED,
        ],
        'activeByFields' => [
            'status' => BOOK_STATUS_ACTIVE,
        ]

    ], $position);

}

function safeDeleteBook($data)
{
    global $query;
    $data = array_merge($data, $query['deleteByFields']);

    actionModel('Books', 'Update', $data);
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

function getBookSingleEdit($params)
{
    global $query;
    $query = [
        'table' => 'book_genres bg',
        'whereEqually' => [
            ['b.status', 'active'],
            ['b.id', $params['id']]
        ],
        'select' => [
            'id'        => 'b.id',
            'title'     =>'b.title',
            'desc_short' => 'b.desc_short',
            'desc_full' => 'b.desc_full',
            'created_at' => 'b.created_at',
            'genres'    => 'GROUP_CONCAT(DISTINCT g.id)',
            'authors'   => 'GROUP_CONCAT(DISTINCT a.id)'
        ],
        'simpleJoin' => '
            RIGHT JOIN books b ON bg.book_id = b.id
            LEFT JOIN genres g ON bg.genre_id = g.id
            LEFT JOIN book_authors ba ON ba.book_id = b.id
            LEFT JOIN authors a ON ba.author_id = a.id
        ',
        'groupBy' => 'bg.book_id',
        'orderBy' => 'b.title'
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

    // создаем книжку с основными данными
    // (заголовок, краткое описание, полное описание)
    $insert = Create($data);

    $id = lastId($insert);
    $objects = ['genre', 'author'];

    if(isset($genres_authors))
        foreach ($objects as $obj)
        {
            if(!array_key_exists($obj, $genres_authors))
                continue;

            // получаем полное имя модели == файла
            $model = getModelHasBook($obj);
            // подключаем модель
            incModel($model);

            if(is_array($genres_authors[$obj]))
            {
                foreach ($genres_authors[$obj] as $addVal)
                {
                    $create['book_id'] = $id;
                    $create[$obj . '_id'] = $addVal;

                    // Вызываем через модель, так нам нужны названия таблиц, стобцов
                    // для жанров и авторов
                    actionModel($model, 'Create', $create);
                    unset($create);
                }
            }
        }

    redirect('admin_group', ['books', 'view']);
}

function saveBook($id)
{
    $data = array();
    filterData($data);

    $data['id'] = $id;
    updateObjects(['genre', 'author'], $data);

    actionModel('Books', 'Update', $data);

    redirect('admin_group', ['books', 'view']);
}



function updateObjects($objects, &$data)
{
    foreach ($objects as $obj)
    {
        if (!array_key_exists($obj, $data['upd']))
            continue;

        $src = array();

        if(array_key_exists('src', $data) && array_key_exists($obj, $data['src']))
            $src = $data['src'][$obj];

        $result[$obj]['remove'] = array_diff($src, $data['upd'][$obj]);
        $result[$obj]['add'] = array_diff($data['upd'][$obj], $src);

        updateObject($result, $obj, $data['id']);

    }
    /** Удаляем элементы с idшниками жанров и авторов,
     *  что бы не мешали при обновлении основной информации книги
     */
    unset($data['src']);
    unset($data['upd']);
}



function updateObject($result, $obj, $id)
{
    // получаем полное имя модели == файла
    $model = getModelHasBook($obj);
    // подключаем модель
    incModel($model);

    if(is_array($result[$obj]['add']))
    {
        foreach ($result[$obj]['add'] as $key => $addVal)
        {

            $create['book_id'] = $id;
            $create[$obj . '_id'] = $addVal;

            // Вызываем через модель, так нам нужны названия таблиц, стобцов
            // для жанров и авторов
            actionModel($model, 'Create', $create);
            unset($create);
        }
    }
    if(is_array($result[$obj]['remove']))
    {
        foreach ($result[$obj]['remove'] as $key => $addVal)
        {

            $remove['book_id'] = $id;
            $remove[$obj . '_id'] = $addVal;

            // Вызываем через модель, так нам нужны названия таблиц, стобцов
            // для жанров и авторов
            actionModel($model, 'Delete', $remove);
            unset($remove);
        }
    }
}

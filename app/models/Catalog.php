<?php


/**
 * Используется в <CatalogController>
 * function <actionIndex()>
 */
function getBooksCatalog()
{
    buildQuery([
        'select' => [
            'id'        => 'b.id',
            'title'     =>'b.title',
            'desc_short' => 'b.desc_short',
            'genres'    => 'GROUP_CONCAT(DISTINCT " ", g.name)',
            'authors'   => 'GROUP_CONCAT(DISTINCT " ", a.name)'
        ],
        'table' => 'book_genres bg',
        'simpleJoin' => '
            LEFT JOIN books b ON bg.book_id = b.id
                LEFT JOIN genres g ON bg.genre_id = g.id
                LEFT JOIN book_authors ba ON ba.book_id = b.id
                LEFT JOIN authors a ON ba.author_id = a.id
        ',
        'whereEqually' => [['b.status', 'active']],
        'groupBy' => 'bg.book_id',
        'orderBy' => 'b.title'
    ]);
}

/**
 * Используется в <CatalogController>
 * function <actionView(params)>
 *
 * @param $params
 */
function getBookSingle($params)
{
    buildQuery([
        'select' => [
            'id'        => 'b.id',
            'title'     =>'b.title',
            'desc_short' => 'b.desc_short',
            'desc_full' => 'b.desc_full',
            'created_at' => 'b.created_at',
            'genres'    => 'GROUP_CONCAT(DISTINCT " ", g.name)',
            'authors'   => 'GROUP_CONCAT(DISTINCT " ", a.name)'
        ],
        'table' => 'book_genres bg',
        'simpleJoin' => '
            LEFT JOIN books b ON bg.book_id = b.id
                LEFT JOIN genres g ON bg.genre_id = g.id
                LEFT JOIN book_authors ba ON ba.book_id = b.id
                LEFT JOIN authors a ON ba.author_id = a.id
        ',
        'whereEqually' => [
            ['b.status', 'active'],
            ['b.id', $params['id']]
        ],
        'groupBy' => 'bg.book_id',
        'orderBy' => 'b.title'
    ]);
}

/**
 * Используется в <AjaxCatalogController>
 * function <ajaxSearchBooks()>
 */
function getBooksCatalogAjax($params)
{

    $placeLikes = array();
    if(array_key_exists('havingLike', $params))
        $placeLikes['havingLike'] = $params['havingLike'];

    if(array_key_exists('name-desc', $params))
        $placeLikes['whereLike'] = ['column' => 'b.title', 'string' => '%'.$params['name-desc'].'%'];

    buildQuery(array_merge($placeLikes, [
        'select' => [
            'id'            => 'b.id',
            'title'         => 'b.title',
            'desc_short'    => 'b.desc_short',
            'genres'        => 'GROUP_CONCAT(DISTINCT " ", g.name)',
            'authors'       => 'GROUP_CONCAT(DISTINCT " ", a.name)',
            'genres_id'     => 'GROUP_CONCAT(DISTINCT "#", g.id, "#")',
            'authors_id'    => 'GROUP_CONCAT(DISTINCT "#", a.id, "#")',
        ],
        'table' => 'book_genres bg',
        'simpleJoin' => '
            LEFT JOIN books b ON bg.book_id = b.id
                LEFT JOIN genres g ON bg.genre_id = g.id
                LEFT JOIN book_authors ba ON ba.book_id = b.id
                LEFT JOIN authors a ON ba.author_id = a.id
        ',
        'whereEqually' => [['b.status', 'active']],
        'groupBy' => 'bg.book_id',
        'orderBy' => 'b.title'
    ]));
}
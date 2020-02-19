<?php

/**
 * @param bool $position
 * TRUE - определение изначального массива настроек <query>
 * FALSE - построение запроса <buildQuery()>
 */
function Catalog($position = true)
{
    mainFuncModel([
        'select' => [
            'id'            => 'b.id',
            'title'         => 'b.title',
            'desc_short'    => 'b.desc_short',
            'desc_full'     => 'b.desc_full',
            'created_at'    => 'b.created_at',
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
    ], $position);
}

/**
 * Используется в <CatalogController>
 * function <actionIndex()>
 */
function getBooksCatalog()
{
    // достаточно вызова главной функции модели
    // можно убрать
}

/**
 * Используется в <CatalogController>
 * function <actionView(params)>
 *
 * @param $params
 */
function getBookSingle($params)
{
    global $query;
    $query['whereEqually'] = [
            ['b.status', 'active'],
            ['b.id', $params['id']]
        ];
}

/**
 * Используется в <AjaxCatalogController>
 * function <ajaxSearchBooks()>
 */
function getBooksCatalogAjax($params)
{
    global $query;

    if(array_key_exists('havingLike', $params))
        $query['havingLike'] = $params['havingLike'];

    if(array_key_exists('name-desc', $params))
        $query['whereLike'] = ['column' => 'b.title', 'string' => '%'.$params['name-desc'].'%'];

}
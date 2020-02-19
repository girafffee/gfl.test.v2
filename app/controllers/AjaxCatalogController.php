<?php

function sortDataForSearch(&$data)
{
    $columns = ['genres', 'authors'];

    foreach ($data as $key => $value)
    {
        $col = explode('-', $key)[0];
        if(in_array($col, $columns))
        {
            $data['havingLike'][] = [
                'column' => $col . '_id',
                'string' => "%#$value#%"
            ];
        }
    }
}

function actionAjaxSearchBooks()
{
    $data = array();
    filterData($data);
    sortDataForSearch($data);

    $books = Get('Catalog', 'getBooksCatalogAjax', $data);

    $genres = All(['table' => 'genres']);
    $authors = All(['table' => 'authors']);

    $content['html'] = render('ajax/catalog.tpl.php', [
        'authors' => $authors,
        'genres' => $genres,
        'books' => $books
    ]);
    $content['success'] = true;

    echo json_encode($content, JSON_UNESCAPED_UNICODE);
    die;
}
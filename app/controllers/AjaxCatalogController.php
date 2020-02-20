<?php

function actionAjaxOrder()
{
    if(empty($_POST))
        return false;

    $data = array();
    filterData($data);

    $book = Get('Books', 'shortInfo', $data)[0];

    $mail_info = [
        'subject'   => 'New order',
        'body'      => render('mail/book_order.php', [
            'book'      => $book,
            'customer'  => $data
        ]),
        'altBody'   => ''
    ];

    if(sendEmailAdmin($mail_info))
        returnAjaxResult(true);
}

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


    renderAjax('ajax/catalog.tpl.php', [
        'authors' => $authors,
        'genres' => $genres,
        'books' => $books
    ]);
}
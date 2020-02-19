<?php

function actionAjaxDeleteBook()
{
    $data = array();
    filterData($data);

    actionModel('Books', 'safeDeleteBook', $data);

    $books = Get('Books', 'getAllBooks');

    $content['html'] = render ('ajax/admin.books.tpl.php', [
        'books' => $books
    ]);
    //print_r($content); die;
    $content['success'] = true;

    echo json_encode($content, JSON_UNESCAPED_UNICODE);
    die;
}

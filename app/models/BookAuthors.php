<?php

function BookAuthors($pos = true)
{
    mainFuncModel([
        'table' => 'book_authors ba',
        'addFields' => ['book_id', 'author_id']
    ], $pos);
}
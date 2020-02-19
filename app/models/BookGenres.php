<?php

function BookGenres($pos = true)
{
    mainFuncModel([
        'table' => 'book_genres ba',
        'addFields' => ['book_id', 'genre_id']
    ], $pos);
}
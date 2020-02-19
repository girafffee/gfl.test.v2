<?php

function Authors($pos = true)
{
    mainFuncModel([
        'select' => [
            'id'            => 'a.id',
            'name'          => 'a.name',
            'created_at'    => 'a.created_at'
        ],
        'table' => 'authors a',
        'orderBy' => 'a.name',
        'addFields' => ['name']
    ], $pos);

}

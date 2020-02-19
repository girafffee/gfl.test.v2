<?php

function Genres($position = true)
{
    mainFuncModel([
        'select' => [
            'id'            => 'g.id',
            'name'          => 'g.name',
            'created_at'    => 'g.created_at'
        ],
        'table' => 'genres g',
        'orderBy' => 'g.name',
        'addFields' => ['name']
    ], $position);
}

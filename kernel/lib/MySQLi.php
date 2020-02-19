<?php

$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

mysqli_set_charset($mysqli, 'utf8');

function simpleExec($sql, $data, $type = 's')
{
    global $mysqli;
    $stmt = mysqli_prepare($mysqli, $sql) or die(mysqli_error($mysqli));

    $types = str_repeat($type, count($data));

    /** @var  $types string
     * Боже, слава интернету, я думал придется писать ужасный костыль
     */
    mysqli_stmt_bind_param($stmt, $types, ...array_values($data));

    /** Выполняем подготовленный запрос */
    mysqli_stmt_execute($stmt);

    return $stmt;
}

function lastId($stmt)
{
    return mysqli_stmt_insert_id($stmt);
}

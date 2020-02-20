<?php

require $mode.'kernel/lib/MySQLi.php';

$query = array();

/**
 * @param $query
 * @return array
 */
function All($query)
{
    global $mysqli;

    $sql = "SELECT * FROM " . $query['table'] . ';';
    return fetchAll($sql);
}

/**
 * @param $sql
 * @return array
 */
function fetchAll($sql)
{
    global $mysqli;
    $result =  mysqli_query($mysqli, $sql);

    $rows = array();
    if($result)
    {
        while ($row = mysqli_fetch_assoc($result))
            $rows[] = $row;
    }

    return $rows;
}

/**
 * @param string $array_selected
 * @return bool
 */
function select($array_selected = '*')
{
    global $query;

    $query['select'] = 'SELECT ';
    if(!is_array($array_selected))
    {
        $query['select'] .= $array_selected . ' ';
        return true;
    }

    $selected = array();
    foreach ($array_selected as $name => $item){
        $selected[] = $item . " as " . "'$name'";
    }
    $query['select'] .= implode(", ", $selected) . ' ';
}

/**
 * @param $name
 */
function table($name)
{
    global $query;

    $name = explode(' ', $name);
    if(count($name) == 2)
    {
        $query['alias'] = $name[1];
    }
    $query['table'] = $name[0];

    $query['from'] = 'FROM '. $query['table'];
    if(array_key_exists('alias', $query))
        $query['from'] .= ' as '. $query['alias'] . ' ';
}

/**
 * @param $simpleJoin
 */
function simpleJoin($simpleJoin)
{
    global $query;
    $query['join'] = $simpleJoin;
}

/**
 * @param $conditions
 */
function whereEqually($conditions)
{
    global $query;
    foreach ($conditions as $cond)
        $query['where'][] = $cond[0] . "='" . $cond[1] ."' ";
}


function whereLike($params)
{
    placeLike('whereLike', $params);
}

function havingLike($params)
{
    placeLike('havingLike', $params);
}

/**
 * @param $params array [
 *      'column' => 'NAME'
 *      'string' => 'VALUE'
 * ]
 * @return mixed
 */
function placeLike($place, $params)
{
    global $query;

    if(count($params, COUNT_RECURSIVE) > 2)
    {
        foreach ($params as $param)
        {
            $string = trim($param['string']);
            if(trim($string, '%') != '')
            {
                $query[$place][$param['column']][] = $param['column']." LIKE '" . rawurldecode(htmlspecialchars($string)). "'";
            }
        }

        return true;
    }
    $string = trim($params['string']);
    if(trim($string, '%') != '')
    {
        $query[$place][$params['column']][] = $params['column']." LIKE '" . rawurldecode(htmlspecialchars($string)). "'";
    }
}

/**
 * @param $column
 */
function groupBy($column)
{
    global $query;

    $query['group_by'] = 'GROUP BY '. $column . ' ';
}

/**
 * @param $column
 */
function orderBy($column)
{
    global $query;

    $query['order_by'] = 'ORDER BY '. $column . ' ';
}

/**
 * @param $options
 * @return array
 */
function buildQuery($options)
{
    global $query, $mysqli;

    $query = array();

    foreach ($options as $callback => $params)
        call($callback, $params);

    requiredOptions();

    return $query;
}

/**
 * @param string $model
 * @param string $queryFunction
 * @param array $params
 * @return array
 */
function Get($model = '', $queryFunction = '', $params = array())
{
    global $query;

    if(!actionModel($model, $queryFunction, $params))
        return array();

    $sqlOrderOptions = ['select', 'from', 'join', 'where', 'group_by', 'having', 'order_by'];

    $sql = '';
    foreach ($sqlOrderOptions as $option)
    {
        if(array_key_exists($option, $query))
            $sql .= $query[$option];
    }
    $sql .= '; ';
    return fetchAll($sql);
}

function actionModel($model, $queryFunction = '', $params = array())
{

    if(incModel($model))
    {
        // основные параметры модели, едины для все функций

        call($model, true);

        // вызываем саму функцию
        call($queryFunction, $params);

        // строим запрос
        call($model, false);
        return true;
    }
}

function mainFuncModel($options, $position = true)
{
    global $query;

    switch ((bool)$position)
    {
        case false:
            buildQuery($query);
            break;
        default:
        case true:
            $query = $options;
            break;
    }

}

/**
 * @param $name
 * @return bool
 */
function incModel($name)
{
    $path = PATH_TO_MODELS . $name . '.php';

    if(file_exists($path))
    {
        require_once $path;
        return true;
    }
    return false;

}


/**
 *
 */
function requiredOptions()
{
    global $query;
    /*if(!array_key_exists('select', $query))
        select();*/

    setLikes();

    if(array_key_exists('where', $query))
        $query['where'] = 'WHERE (' . implode(') AND (', $query['where']) . ') ';

    if(array_key_exists('having', $query))
        $query['having'] = 'HAVING ' . $query['having'][0] . ' ';

}

function setLikes()
{
    global $query;

    $places = [
        'whereLike' => 'where',
        'havingLike' => 'having'
    ];
    foreach ($places as $old_place => $new_place)
        if(array_key_exists($old_place, $query))
        {
            $where_groups = [];
            foreach ($query[$old_place] as $column => $conditions)
            {
                $where_groups[$column] = ' (' . implode(' OR ', $conditions) . ') ';
            }

            $query[$new_place][] = implode("\n AND \n", $where_groups);
            unset($query[$old_place]);
        }

}

function Create($data)
{
    global $query;

    if(!array_key_exists('addFields', $query))
        return NULL;

    table($query['table']);
    $addFields = $query['addFields'];

    // строка из вопросительный знаков через запятую для подставления значений
    $questions = implode(',', array_fill(0, count($addFields), '?'));

    $sql = "INSERT INTO " . $query['table'] . ' '." (";
    $sql .= implode(", ", $addFields);
    $sql .= ") VALUES ($questions);";

    return simpleExec($sql, $data);
}

function Delete($data)
{
    global $query;

    table($query['table']);

    $sql = "DELETE FROM " . $query['table'] . " WHERE ";

    foreach ($data as $col => $value)
    {
        $conditions[] = $col.' = ?';
    }
    $sql .= implode(' AND ', $conditions);
    $sql .= ';';

    return simpleExec($sql, $data);
}
function Update($data)
{
    global $query;

    table($query['table']);

    $sql = "UPDATE " . $query['table'] . " SET ";

    foreach ($data as $col => $value)
    {
        if($col != $query['findField'])
            $newValues[] = "$col = ?";

    }
    $sql .= implode(", ", $newValues);
    $sql .= " WHERE " . $query['findField'] . '=' . $data[$query['findField']];
    unset($data[$query['findField']]);
    $sql .= '; ';

    return simpleExec($sql, $data);
}



function getModelHasBook($object)
{
    return 'Book'. ucfirst($object) . 's';
}

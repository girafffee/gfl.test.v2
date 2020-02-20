<?php

function call($callback, $params = array())
{
    if(function_exists($callback))
    {
        $callback($params);
        return true;
    }
}

/**
 * Выводит массив в виде дерева
 *
 * @param mixed - Массив или объект, который надо обойти
 * @param boolean - Раскрыть дерево элементов по-умолчанию или нет?
 *
 * @return void
 */
function vg($in,$opened = true){
    $str = '';
    if($opened)
        $opened = ' open';
    if(is_object($in) or is_array($in)){
        $str .= '<div>';
        $str .= '<details'.$opened.'>';
        $str .= '<summary>';
        $str .= (is_object($in)) ? 'Object {'.count((array)$in).'}':'Array ['.count($in).']';
        $str .= '</summary>';
        $str .= pretty_print_rec($in, $opened);
        $str .= '</details>';
        $str .= '</div>';
    }
    echo $str;
    die;
}
function pretty_print_rec($in, $opened, $margin = 10){
    if(!is_object($in) && !is_array($in))
        return;
    $str = '';
    foreach($in as $key => $value){
        if(is_object($value) or is_array($value)){
            $str .= '<details style="margin-left:'.$margin.'px" '.$opened.'>';
            $str .= '<summary>';
            $str .= (is_object($value)) ? $key.' {'.count((array)$value).'}':$key.' ['.count($value).']';
            $str .= '</summary>';
            pretty_print_rec($value, $opened, $margin+10);
            $str .= '</details>';
        }
        else{
            switch(gettype($value)){
                case 'string':
                    $bgc = 'red';
                    break;
                case 'integer':
                    $bgc = 'green';
                    break;
            }
            $str .= '<div style="margin-left:'.$margin.'px">'.$key . ' : <span style="color:'.$bgc.'">' . $value .'</span> ('.gettype($value).')</div>';
        }
    }
    return $str;
}
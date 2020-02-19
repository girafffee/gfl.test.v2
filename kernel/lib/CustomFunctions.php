<?php

function call($callback, $params = array())
{
    if(function_exists($callback))
    {
        $callback($params);
        return true;
    }
}
<?php

class Security
{
    //Converte/tira sql injection
    function fix_string($str)
    {
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        $simbols = array("'", "\"", "{", "}", "(", ")", ">", "<", ",", ".", " ");
        $str = str_replace($simbols, "", $str);
        return $str;
    }
}

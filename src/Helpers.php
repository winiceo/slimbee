<?php
/**
 * Created by PhpStorm.
 * User: leven
 * Date: 15/7/7
 * Time: 涓???7:34
 */


function dump($var, $echo = true, $label = null, $strict = true)
{
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'
                . $label
                . htmlspecialchars($output, ENT_QUOTES)
                . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    } else {
        return $output;
    }
}

function C($key = NULL, $value = NULL)
{
    static $_config = array();

    if (is_array($key)) {
        return $_config = array_merge($_config, array_change_key_case($key, CASE_UPPER));
    }
    $key = strtoupper($key);
    if (!is_null($value)) {
        return $_config[$key] = $value;
    }
    if (empty($key)) {
        return $_config;
    }
    return isset($_config[$key]) ? $_config[$key] : NULL;
}


//生成验证码
function generate_code($length = 6) {
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}

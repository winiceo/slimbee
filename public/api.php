<?php
use App\Application;
use Symfony\Component\Dotenv\Dotenv;

header('Content-type: text/html;charset=utf-8');

session_start();

require __DIR__.'/../vendor/autoload.php';

if (!isset($_SERVER['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv())->load(__DIR__.'/../.env');
}


function C($key = NULL, $value = NULL)
{
    static $_config = array();

    if (is_array($key)) {
        return $_config = array_merge($_config,  ($key));
    }
    $key =  ($key);
    if (!is_null($value)) {
        return $_config[$key] = $value;
    }
    if (empty($key)) {
        return $_config;
    }
    return isset($_config[$key]) ? $_config[$key] : NULL;
}


$app = new Application($_SERVER['APP_ENV'] ?? 'dev');


$app->run();
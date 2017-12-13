<?php
/**
 * User: zhangshize
 * Date: 2016/12/30
 * Time: 下午 3:34
 */

namespace Leven;

/**
 * Class View
 * If you want to use this facades, you should set a 'logger' service in the
 * container.
 * @package Leven
 */
class Log extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'monolog';
    }
}
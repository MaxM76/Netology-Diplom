<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

//echo 'index.php'.'<br/>';

class Di
{

    static $di = null;

    function __construct()
    {
        //echo 'di constructed<br/>';
     }

    function __destruct()
    {
        //echo 'di destructed!!!!!!!!!!!!!!!!!!<br/>';
    }

 
    public static function get()
    {
        if (! self::$di) {
            self::$di = new Di();
        }
        return self::$di;
    }
 
    public function render($template, $params = [])
    {
        $result = '';
        $fileTemplate = 'template/'.$template;
        if (is_file($fileTemplate)) {
            ob_start();
            if (count($params) > 0) {
                extract($params);
            }
            include $fileTemplate;               
            $result = ob_get_clean();
        }
        return $result;
    }
}

include 'router/router.php';

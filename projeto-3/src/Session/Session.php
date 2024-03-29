<?php
namespace Code\Session;

class Session
{
    public static function sessionStart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function add($key, $value)
    {
        self::sessionStart();
        $_SESSION[$key] = $value;
    }

    public static function remove($key)
    {
        self::sessionStart();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function has($key)
    {
        self::sessionStart();
        return isset($_SESSION[$key]);
    }

    public static function get($key)
    {
        self::sessionStart();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        self::sessionStart();
        session_destroy();
    }
}

<?php

declare (strict_types = 1);

namespace Phantom\Helper;

class Session
{
    # Method checks if exists session with given name
    public static function has($name): bool
    {
        if (isset($_SESSION[$name]) && !empty($_SESSION[$name])) {return true;} else {return false;}
    }

    # Method checks if exists sessions with given names
    public static function hasArray(array $names): bool
    {
        foreach ($names as $name) {
            if (!Session::has($name)) {return false;}
        }

        return true;
    }

    # Method returns value of session with given name
    public static function get($name)
    {
        if (Session::has($name) == true) {
            return $_SESSION[$name];
        } else {
            return null;
        }
    }

    # Method returns value of session with given name and next unsets it
    public static function getNextClear($name)
    {
        $value = Session::get($name);
        Session::clear($name);
        return $value;
    }

    # Method sets value of session with given name
    public static function set($name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    # Short method to set success message
    public static function success(string $message)
    {
        Session::set('success', $message);
    }

    # Short method to set error message
    public static function error(string $message)
    {
        Session::set('error', $message);
    }

    # Method unset session with given name
    public static function clear($name): void
    {
        unset($_SESSION[$name]);
    }

    # Method unset session with given names
    public static function clearArray(array $names): void
    {
        foreach ($names as $name) {
            Session::clear($name);
        }
    }
}

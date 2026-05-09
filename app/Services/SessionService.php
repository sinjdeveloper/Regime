<?php

namespace App\Services;

class SessionService
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function initSignupDraft()
    {
        self::start();

        if (!isset($_SESSION['signup_draft'])) {
            $_SESSION['signup_draft'] = [
                'user' => [],
                'health' => [],
                'goal' => []
            ];
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        self::start();
        session_destroy();
    }

    // BONUS: nested setter (VERY useful for signup_draft.user)
    public static function setNested($path, $value)
    {
        self::start();

        $keys = explode('.', $path);
        $temp = &$_SESSION;

        foreach ($keys as $key) {
            if (!isset($temp[$key]) || !is_array($temp[$key])) {
                $temp[$key] = [];
            }
            $temp = &$temp[$key];
        }

        $temp = $value;
    }

    public static function getNested($path, $default = null)
    {
        self::start();

        $keys = explode('.', $path);
        $temp = $_SESSION;

        foreach ($keys as $key) {
            if (!isset($temp[$key])) {
                return $default;
            }
            $temp = $temp[$key];
        }

        return $temp;
    }

    public static function updateGoals($goals)
    {
        self::start();
        $_SESSION['signup_draft']['goals'] = is_array($goals) ? $goals : [];
    }

    public static function createUserSession($user)
    {
        self::start();

        $_SESSION['user'] = $user;
    }

    public static function clearSignupDraft()
    {
        self::start();

        unset($_SESSION['signup_draft']);
    }
}

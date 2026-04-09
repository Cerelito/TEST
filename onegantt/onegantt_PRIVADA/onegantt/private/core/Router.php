<?php
class Router
{
    public static function url(string $path = '', int|string $id = null): string
    {
        $url = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
        if ($id !== null) $url .= '/' . $id;
        return $url;
    }

    public static function redirect(string $path = '', int|string $id = null): never
    {
        header('Location: ' . self::url($path, $id));
        exit;
    }

    public static function redirectWithFlash(string $path, string $message, string $type = 'success'): never
    {
        $_SESSION['flash'] = ['msg' => $message, 'type' => $type];
        self::redirect($path);
    }

    public static function flash(): ?array
    {
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}

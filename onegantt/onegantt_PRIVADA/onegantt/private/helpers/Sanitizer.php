<?php
// ── Sanitizer.php ─────────────────────────────────────────
class Sanitizer
{
    public static function string(string $val): string
    {
        return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
    }

    public static function int(mixed $val): int
    {
        return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function email(string $val): string
    {
        return strtolower(trim(filter_var($val, FILTER_SANITIZE_EMAIL)));
    }

    public static function filename(string $val): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', basename($val));
    }

    public static function post(string $key, string $type = 'string'): mixed
    {
        $val = $_POST[$key] ?? '';
        return match($type) {
            'int'   => self::int($val),
            'email' => self::email($val),
            default => self::string($val),
        };
    }
}

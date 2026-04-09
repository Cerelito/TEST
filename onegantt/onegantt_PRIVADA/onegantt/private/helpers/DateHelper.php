<?php
class DateHelper
{
    public static function format(string $date, string $fmt = 'd/m/Y'): string
    {
        if (empty($date)) return '—';
        return date($fmt, strtotime($date));
    }

    public static function daysLeft(string $date): int
    {
        $diff = strtotime($date) - strtotime('today');
        return (int) ceil($diff / 86400);
    }

    public static function isOverdue(string $date): bool
    {
        return !empty($date) && strtotime($date) < strtotime('today');
    }

    public static function isDueSoon(string $date, int $days = 3): bool
    {
        $left = self::daysLeft($date);
        return $left >= 0 && $left <= $days;
    }

    public static function toInput(string $date): string
    {
        if (empty($date)) return '';
        return date('Y-m-d', strtotime($date));
    }
}

<?php

// ── Load .env file if present ─────────────────────────────────────────────────
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line === '' || $line[0] === '#') continue;
        if (!str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $key = trim($key);
        $val = trim($val, " \t\n\r\0\x0B\"'");
        if ($key !== '' && !isset($_ENV[$key])) {
            $_ENV[$key] = $val;
            putenv("$key=$val");
        }
    }
}
unset($envFile, $line, $key, $val);

define('APP_NAME', 'EK Accesos');
define('APP_VERSION', '3.0');
define('EK_CRYPT_KEY', $_ENV['EK_CRYPT_KEY'] ?? 'ek_accesos_crypt_2024_change_me!!');
define('BASE_URL',     $_ENV['BASE_URL']     ?? 'https://apotemaone.com/urbano/ekusers');
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views/');
define('HELPERS_PATH', ROOT_PATH . '/helpers/');
define('SESSION_LIFETIME', 3600);
define('ADMIN_EMAIL',     $_ENV['ADMIN_EMAIL']     ?? 'ecruz@urbanopark.com');
define('MAIL_HOST',       $_ENV['MAIL_HOST']       ?? 'smtp.gmail.com');
define('MAIL_PORT',  (int)($_ENV['MAIL_PORT']       ?? 587));
define('MAIL_USER',       $_ENV['MAIL_USER']       ?? 'noreply@ekaccesos.com');
define('MAIL_PASS',       $_ENV['MAIL_PASS']       ?? '');
define('MAIL_FROM_NAME',  $_ENV['MAIL_FROM_NAME']  ?? 'EK Accesos');

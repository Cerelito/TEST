<?php
define('APP_NAME',    'OneGantt');
define('APP_VERSION', '1.0.0');
define('BASE_URL',    'https://www.apotemaone.com/urbano/onegantt');
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH',   '/home1/erickedu/Controladores-Apotema/onegantt/');
}
define('UPLOAD_PATH', '/home1/erickedu/public_html/urbano/onegantt/uploads/');
define('UPLOAD_URL',  BASE_URL . '/uploads/');
define('LOG_PATH',    ROOT_PATH . 'logs/app.log');

define('ALLOWED_MIME', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'image/jpeg', 'image/png', 'image/gif',
    'text/plain', 'text/csv',
]);

define('MAX_UPLOAD_MB',    5);
define('MAX_UPLOAD_BYTES', MAX_UPLOAD_MB * 1024 * 1024);
define('SESSION_NAME',     'og_session');
define('SESSION_LIFETIME', 28800);

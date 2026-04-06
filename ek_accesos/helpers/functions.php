<?php
/**
 * General-purpose helper functions for EK Accesos.
 */

// ─────────────────────────────────────────────────────────────────────────────
// Navigation
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Redirect to a URL and terminate execution.
 *
 * @param string $path  Absolute URL or path relative to BASE_URL.
 */
function redirect(string $path): void
{
    if (!preg_match('#^https?://#i', $path)) {
        $path = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }

    header('Location: ' . $path, true, 302);
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Flash messages
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Store a flash message in the session.
 * Messages are consumed once by getFlash().
 *
 * @param string $type     Category: 'success' | 'error' | 'warning' | 'info'
 * @param string $message  Human-readable message text.
 */
function setFlash(string $type, string $message): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['_flash'][] = [
        'type'    => $type,
        'message' => $message,
    ];
}

/**
 * Return all pending flash messages and remove them from the session.
 *
 * @return array<int, array{type: string, message: string}>
 */
function getFlash(): array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $messages = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);

    return $messages;
}

// ─────────────────────────────────────────────────────────────────────────────
// String utilities
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Sanitize a string by trimming whitespace and escaping HTML special characters.
 *
 * @param string $str  Raw input string.
 * @return string      Safe, trimmed string.
 */
function sanitize(string $str): string
{
    return htmlspecialchars(trim($str), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ─────────────────────────────────────────────────────────────────────────────
// Formatting
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Format a numeric amount as Mexican Peso currency.
 *
 * @param float|int|string $amount  Numeric amount.
 * @return string                   e.g. "$1,234.56 MXN"
 */
function formatMoney(float|int|string $amount): string
{
    $amount = (float) $amount;

    if (class_exists('NumberFormatter')) {
        $formatter = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        $formatted = $formatter->formatCurrency($amount, 'MXN');
        if ($formatted !== false) {
            return $formatted;
        }
    }

    return '$' . number_format($amount, 2, '.', ',') . ' MXN';
}

// ─────────────────────────────────────────────────────────────────────────────
// Security
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Generate a cryptographically secure random hex token.
 *
 * @param int $length  Number of random bytes (output string is 2× this length).
 * @return string      Lowercase hexadecimal string.
 */
function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

/**
 * Encrypt a short string (ERP password/PIN) using AES-256-CBC.
 * Returns a base64-encoded string safe for DB storage.
 */
function encryptEK(string $plain): string
{
    if ($plain === '') return '';
    $key       = hash('sha256', defined('EK_CRYPT_KEY') ? EK_CRYPT_KEY : 'fallback', true);
    $iv        = random_bytes(16);
    $encrypted = openssl_encrypt($plain, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt a string previously encrypted with encryptEK().
 */
function decryptEK(string $ciphertext): string
{
    if ($ciphertext === '') return '';
    $key  = hash('sha256', defined('EK_CRYPT_KEY') ? EK_CRYPT_KEY : 'fallback', true);
    $data = base64_decode($ciphertext, true);
    if ($data === false || strlen($data) < 17) return '';
    $iv  = substr($data, 0, 16);
    $enc = substr($data, 16);
    return openssl_decrypt($enc, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv) ?: '';
}

// ─────────────────────────────────────────────────────────────────────────────
// Date & time
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Return a human-readable "time ago" string in Spanish for a given datetime.
 *
 * @param string|\DateTimeInterface $datetime  MySQL datetime string or DateTimeInterface.
 * @return string  e.g. "hace 3 minutos", "hace 2 horas", "hace 1 día"
 */
function timeAgo(string|\DateTimeInterface $datetime): string
{
    if (is_string($datetime)) {
        $datetime = new DateTime($datetime);
    }

    $now  = new DateTime();
    $diff = $now->getTimestamp() - $datetime->getTimestamp();

    if ($diff < 0) {
        return 'en un momento';
    }

    return match (true) {
        $diff < 60         => 'hace un momento',
        $diff < 3600       => 'hace ' . floor($diff / 60)   . ' ' . _plural(floor($diff / 60),   'minuto',  'minutos'),
        $diff < 86400      => 'hace ' . floor($diff / 3600)  . ' ' . _plural(floor($diff / 3600),  'hora',    'horas'),
        $diff < 604800     => 'hace ' . floor($diff / 86400) . ' ' . _plural(floor($diff / 86400), 'día',     'días'),
        $diff < 2592000    => 'hace ' . floor($diff / 604800)  . ' ' . _plural(floor($diff / 604800),  'semana',  'semanas'),
        $diff < 31536000   => 'hace ' . floor($diff / 2592000) . ' ' . _plural(floor($diff / 2592000), 'mes',     'meses'),
        default            => 'hace ' . floor($diff / 31536000) . ' ' . _plural(floor($diff / 31536000), 'año', 'años'),
    };
}

/**
 * Internal helper: returns singular or plural form based on count.
 *
 * @internal
 */
function _plural(int|float $count, string $singular, string $plural): string
{
    return $count === 1.0 || $count === 1 ? $singular : $plural;
}

// ─────────────────────────────────────────────────────────────────────────────
// Pagination
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Calculate pagination metadata.
 *
 * @param int $total        Total number of records.
 * @param int $perPage      Records per page.
 * @param int $currentPage  Current page number (1-based).
 *
 * @return array{
 *   total:        int,
 *   per_page:     int,
 *   current_page: int,
 *   last_page:    int,
 *   offset:       int,
 *   has_prev:     bool,
 *   has_next:     bool,
 *   prev_page:    int|null,
 *   next_page:    int|null,
 *   pages:        int[],
 * }
 */
function paginate(int $total, int $perPage, int $currentPage): array
{
    $perPage     = max(1, $perPage);
    $currentPage = max(1, $currentPage);
    $lastPage    = (int) ceil($total / $perPage);
    $lastPage    = max(1, $lastPage);
    $currentPage = min($currentPage, $lastPage);
    $offset      = ($currentPage - 1) * $perPage;

    // Build a sensible page window (±2 around current page)
    $window = 2;
    $start  = max(1, $currentPage - $window);
    $end    = min($lastPage, $currentPage + $window);
    $pages  = range($start, $end);

    return [
        'total'        => $total,
        'per_page'     => $perPage,
        'current_page' => $currentPage,
        'last_page'    => $lastPage,
        'offset'       => $offset,
        'has_prev'     => $currentPage > 1,
        'has_next'     => $currentPage < $lastPage,
        'prev_page'    => $currentPage > 1 ? $currentPage - 1 : null,
        'next_page'    => $currentPage < $lastPage ? $currentPage + 1 : null,
        'pages'        => $pages,
    ];
}

// ─────────────────────────────────────────────────────────────────────────────
// Email
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Send an HTML email, using PHPMailer if available, else PHP mail().
 */
function sendEmail(string $to, string $subject, string $body): bool
{
    // Try PHPMailer via Composer autoload or manual path
    $phpMailerPaths = [
        defined('ROOT_PATH') ? ROOT_PATH . '/vendor/autoload.php'                              : null,
        defined('ROOT_PATH') ? ROOT_PATH . '/vendor/phpmailer/phpmailer/src/PHPMailer.php'     : null,
    ];

    foreach ($phpMailerPaths as $path) {
        if ($path && file_exists($path)) {
            try {
                require_once $path;
                $smtpFile = dirname($path) . '/SMTP.php';
                $excFile  = dirname($path) . '/Exception.php';
                if (file_exists($smtpFile)) require_once $smtpFile;
                if (file_exists($excFile))  require_once $excFile;

                if (!class_exists('\PHPMailer\PHPMailer\PHPMailer')) break;

                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = defined('MAIL_HOST') ? MAIL_HOST : 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = defined('MAIL_USER') ? MAIL_USER : '';
                $mail->Password   = defined('MAIL_PASS') ? MAIL_PASS : '';
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = defined('MAIL_PORT') ? (int)MAIL_PORT : 587;
                $mail->CharSet    = 'UTF-8';
                $mail->setFrom(
                    defined('MAIL_USER')      ? MAIL_USER      : 'noreply@ekaccesos.com',
                    defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'EK Accesos'
                );
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $body;
                $mail->AltBody = strip_tags($body);
                return $mail->send();
            } catch (\Throwable $e) {
                error_log('[EK Accesos] PHPMailer error: ' . $e->getMessage());
                break;
            }
        }
    }

    // Fallback: native mail()
    $fromName = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'EK Accesos';
    $fromAddr = defined('MAIL_USER')      ? MAIL_USER      : 'noreply@ekaccesos.com';
    $headers  = implode("\r\n", [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: ' . $fromName . ' <' . $fromAddr . '>',
        'X-Mailer: PHP/' . PHP_VERSION,
    ]);
    return @mail($to, $subject, $body, $headers);
}

// ─────────────────────────────────────────────────────────────────────────────
// Audit Log
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Insert an action record into the audit log table.
 */
function logAction(string $accion, string $modulo, string $descripcion = ''): void
{
    try {
        $pdo    = Database::getInstance();
        $evento = $modulo ? "{$accion}:{$modulo}" : $accion;
        $pdo->prepare(
            "INSERT INTO logs_acceso (usuario_id, evento, descripcion, ip, user_agent, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        )->execute([
            currentUserId(),
            $evento,
            $descripcion,
            $_SERVER['REMOTE_ADDR']     ?? '0.0.0.0',
            $_SERVER['HTTP_USER_AGENT'] ?? '',
        ]);
    } catch (\Throwable $e) {
        error_log('[EK Accesos] logAction failed: ' . $e->getMessage());
    }
}

/**
 * Verify CSRF token on the current POST request.
 * Delegates to verifyCSRF() defined in helpers/csrf.php (loaded first).
 * If csrf.php is not yet loaded, performs the check inline.
 */
if (!function_exists('verifyCSRF')) {
    function verifyCSRF(bool $regenerate = false): bool
    {
        $submitted = $_POST[defined('CSRF_POST_KEY') ? CSRF_POST_KEY : '_csrf_token'] ?? '';
        $stored    = $_SESSION[defined('CSRF_SESSION_KEY') ? CSRF_SESSION_KEY : '_csrf_token'] ?? '';

        if (empty($submitted) || empty($stored) || !hash_equals($stored, $submitted)) {
            http_response_code(403);
            echo '<h1>403 — Solicitud inválida</h1>';
            echo '<p>Token de seguridad inválido o expirado. Recarga la página e inténtalo de nuevo.</p>';
            exit;
        }

        if ($regenerate) {
            unset($_SESSION[defined('CSRF_SESSION_KEY') ? CSRF_SESSION_KEY : '_csrf_token']);
        }

        return true;
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Pending approvals badge
// ─────────────────────────────────────────────────────────────────────────────

function pendingApprovals(): int
{
    try {
        return (int) getDB()->query(
            "SELECT COUNT(*) FROM usuarios WHERE aprobado=0 AND activo=0 AND deleted_at IS NULL"
        )->fetchColumn();
    } catch (\Throwable $e) {
        return 0;
    }
}

function isActive(string $path): string
{
    $current = trim($_SERVER['REQUEST_URI'] ?? '', '/');
    $base    = trim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    $current = ltrim(str_replace($base, '', '/' . $current), '/');
    return (strpos($current, $path) === 0) ? 'active' : '';
}

// ─────────────────────────────────────────────────────────────────────────────
// Role helper (proxy; auth.php may define this too — guard against re-declare)
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('isRole')) {
    /**
     * Return true if the current user holds at least one of the given roles.
     * superadmin and admin bypass all checks.
     */
    function isRole(string|array $roles): bool
    {
        if (!function_exists('isLoggedIn') || !isLoggedIn()) return false;
        $roles       = is_array($roles) ? $roles : [$roles];
        $currentRole = $_SESSION['user']['rol']
                    ?? $_SESSION['user']['tipo_usuario']
                    ?? '';
        if (in_array($currentRole, ['superadmin', 'admin'], true)) return true;
        return in_array($currentRole, $roles, true);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Session login/logout stubs (primary definitions live in auth.php)
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('loginUser')) {
    /** Store an authenticated user in the session. */
    function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['_last_regenerated'] = time();
        $_SESSION['user']              = $user;
    }
}

if (!function_exists('logoutUser')) {
    /** Destroy the session and clear the cookie. */
    function logoutUser(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}

if (!function_exists('isLoggedIn')) {
    /** Return true when an authenticated user is present in the session. */
    function isLoggedIn(): bool
    {
        return !empty($_SESSION['user']['id']);
    }
}

<?php
/**
 * CSRF (Cross-Site Request Forgery) protection helpers for EK Accesos.
 *
 * Usage:
 *   - Call generateCSRF() once per form render to obtain (or reuse) the token.
 *   - Include csrfField() inside every HTML <form> that performs state changes.
 *   - Call verifyCSRF() at the top of every POST handler.
 */

define('CSRF_SESSION_KEY', '_csrf_token');
define('CSRF_POST_KEY',    '_csrf_token');  // Name of the hidden input field

// ---------------------------------------------------------------------------
// Token generation
// ---------------------------------------------------------------------------

/**
 * Return the current session CSRF token, generating a new one if needed.
 * The token is stored in $_SESSION and is valid for the lifetime of the session.
 *
 * @return string  64-character hexadecimal token.
 */
function generateCSRF(): string
{
    if (empty($_SESSION[CSRF_SESSION_KEY])) {
        $_SESSION[CSRF_SESSION_KEY] = bin2hex(random_bytes(32));
    }

    return $_SESSION[CSRF_SESSION_KEY];
}

// ---------------------------------------------------------------------------
// Token verification
// ---------------------------------------------------------------------------

/**
 * Validate the CSRF token submitted with a POST request.
 *
 * Compares the value in $_POST[CSRF_POST_KEY] against the stored session
 * token using a timing-safe comparison to prevent timing attacks.
 *
 * On failure the function logs the event, sends a 403 response, and
 * terminates execution.
 *
 * @param bool $regenerate  When true, regenerate the token after a successful
 *                          verification (one-time tokens).  Default: false.
 * @return bool             Returns true on success (also terminates on failure).
 */
function verifyCSRF(bool $regenerate = false): bool
{
    $submitted = $_POST[CSRF_POST_KEY] ?? '';
    $stored    = $_SESSION[CSRF_SESSION_KEY] ?? '';

    if (
        empty($submitted)
        || empty($stored)
        || !hash_equals($stored, $submitted)
    ) {
        error_log(sprintf(
            '[CSRF] Token mismatch for %s %s – IP: %s',
            $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
            $_SERVER['REQUEST_URI']    ?? '/',
            $_SERVER['REMOTE_ADDR']    ?? 'unknown'
        ));

        http_response_code(403);

        $viewFile = VIEWS_PATH . 'errors/403.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo '<h1>403 – Solicitud inválida</h1>';
            echo '<p>Token de seguridad inválido o expirado. Por favor, recarga la página e inténtalo de nuevo.</p>';
        }

        exit;
    }

    if ($regenerate) {
        // Invalidate the old token so it cannot be reused
        unset($_SESSION[CSRF_SESSION_KEY]);
        generateCSRF();
    }

    return true;
}

// ---------------------------------------------------------------------------
// HTML helper
// ---------------------------------------------------------------------------

/**
 * Return an HTML hidden input field containing the current CSRF token.
 * Automatically generates the token if it does not yet exist.
 *
 * Example usage inside a Blade/plain-PHP template:
 *   <form method="POST" action="/users/store">
 *       <?= csrfField() ?>
 *       …
 *   </form>
 *
 * @return string  HTML string: <input type="hidden" name="_csrf_token" value="…">
 */
function csrfField(): string
{
    $token = generateCSRF();
    $name  = htmlspecialchars(CSRF_POST_KEY, ENT_QUOTES, 'UTF-8');
    $value = htmlspecialchars($token,        ENT_QUOTES, 'UTF-8');

    return sprintf(
        '<input type="hidden" name="%s" value="%s">',
        $name,
        $value
    );
}

/**
 * Convenience alias: return just the raw token value (useful for AJAX headers).
 *
 * @return string  The current CSRF token.
 */
function csrfToken(): string
{
    return generateCSRF();
}

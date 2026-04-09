<?php
/**
 * Mailer — Implementación SMTP por sockets nativos (sin dependencias).
 * Soporta STARTTLS y Autenticación LOGIN para Google Workspace.
 */
class Mailer
{
    private array $cfg;

    public function __construct()
    {
        $this->cfg = require ROOT_PATH . 'config/mail.php';
    }

    /**
     * Enviar correo vía SMTP Autenticado
     */
    public function send(string $to, string $subject, string $bodyHtml, string $bodyText = ''): bool
    {
        $cfg = $this->cfg;
        $fromEmail = $cfg['from_email'];
        $fromName  = $cfg['from_name'];
        $uid = md5(uniqid((string)time()));

        // Construir cabeceras
        $headers = [
            "From: {$fromName} <{$fromEmail}>",
            "Reply-To: {$fromEmail}",
            "To: {$to}",
            "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
            "MIME-Version: 1.0",
            "Content-Type: multipart/alternative; boundary=\"{$uid}\"",
            "X-Mailer: OneGantt/SMTP"
        ];

        $bodyText = $bodyText ?: strip_tags($bodyHtml);

        $bodyContent  = "--{$uid}\r\n";
        $bodyContent .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n{$bodyText}\r\n";
        $bodyContent .= "--{$uid}\r\n";
        $bodyContent .= "Content-Type: text/html; charset=UTF-8\r\n\r\n{$bodyHtml}\r\n";
        $bodyContent .= "--{$uid}--";

        $fullPayload = implode("\r\n", $headers) . "\r\n\r\n" . $bodyContent . "\r\n.";

        return $this->smtpExecute($to, $fullPayload);
    }

    /**
     * Motor SMTP vía Sockets
     */
    private function smtpExecute(string $to, string $payload): bool
    {
        $cfg = $this->cfg;
        $host = $cfg['host'];
        $port = $cfg['port'];
        $user = $cfg['username'];
        $pass = $cfg['password'];

        $socket = @fsockopen($host, $port, $errno, $errstr, 15);
        if (!$socket) {
            error_log("[OneGantt] SMTP: Fallo conexión {$host}:{$port} - {$errstr}");
            return false;
        }

        $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $this->getResponse($socket, "Conexión inicial");
        $this->sendCommand($socket, "EHLO " . $serverName, 250);

        // TLS
        if ($cfg['encryption'] === 'tls') {
            $this->sendCommand($socket, "STARTTLS", 220);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_ANY_CLIENT)) {
                error_log("[OneGantt] SMTP: Fallo al activar TLS");
                fclose($socket);
                return false;
            }
            $this->sendCommand($socket, "EHLO " . $serverName, 250);
        }

        // Auth
        $this->sendCommand($socket, "AUTH LOGIN", 334);
        $this->sendCommand($socket, base64_encode($user), 334);
        $this->sendCommand($socket, base64_encode($pass), 235);

        // Mail
        $this->sendCommand($socket, "MAIL FROM:<{$cfg['username']}>", 250);
        $this->sendCommand($socket, "RCPT TO:<{$to}>", 250);

        // Data
        $this->sendCommand($socket, "DATA", 354);
        fwrite($socket, $payload . "\r\n");
        $this->getResponse($socket, "DATA Payload", 250);

        // Quit
        fwrite($socket, "QUIT\r\n");
        fclose($socket);

        return true;
    }

    private function sendCommand($socket, $cmd, $expectedCode) {
        fwrite($socket, $cmd . "\r\n");
        return $this->getResponse($socket, $cmd, $expectedCode);
    }

    private function getResponse($socket, $context, $expectedCode = null) {
        $resp = "";
        while ($line = fgets($socket, 512)) {
            $resp .= $line;
            if (substr($line, 3, 1) == " ") break;
        }
        $code = (int)substr($resp, 0, 3);
        if ($expectedCode && $code !== $expectedCode) {
            throw new Exception("SMTP Error [$context]: Expected $expectedCode but got $code. Resp: $resp");
        }
        return $resp;
    }

    /**
     * Plantilla de alerta de tarea por vencer
     */
    public function sendTaskReminder(array $user, array $task): bool
    {
        $subject = "[OneGantt] Recordatorio: \"{$task['titulo']}\" vence pronto";
        $html = "
        <div style='font-family:sans-serif;max-width:560px;margin:auto;'>
          <div style='background:#5563DE;padding:20px 28px;border-radius:8px 8px 0 0;'>
            <h1 style='color:#fff;font-size:18px;margin:0;'>OneGantt · Recordatorio</h1>
          </div>
          <div style='background:#f9f9f9;padding:24px 28px;border-radius:0 0 8px 8px;border:1px solid #e5e5e5;'>
            <p style='color:#333;'>Hola <strong>{$user['nombre']}</strong>,</p>
            <p style='color:#555;'>La siguiente tarea está próxima a vencer:</p>
            <div style='background:#fff;border-left:4px solid #5563DE;padding:14px 18px;border-radius:4px;margin:16px 0;'>
              <strong style='color:#222;'>{$task['titulo']}</strong><br>
              <span style='color:#888;font-size:13px;'>Proyecto: {$task['proyecto']}</span><br>
              <span style='color:#e53e3e;font-size:13px;'>Vence: {$task['fecha_fin']}</span>
            </div>
            <a href='" . BASE_URL . "/tasks/edit/{$task['id']}'
               style='display:inline-block;background:#5563DE;color:#fff;padding:10px 22px;border-radius:6px;text-decoration:none;font-size:14px;'>
              Ver tarea
            </a>
            <p style='color:#aaa;font-size:12px;margin-top:24px;'>Este es un mensaje automático de OneGantt · Apotema Lab</p>
          </div>
        </div>";

        try {
            return $this->send($user['email'], $subject, $html);
        } catch (Exception $e) {
            error_log("[OneGantt] Mailer Error: " . $e->getMessage());
            return false;
        }
    }
}

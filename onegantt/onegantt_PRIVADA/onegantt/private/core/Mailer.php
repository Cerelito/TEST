<?php
/**
 * Mailer — SMTP nativo (sin dependencias externas).
 * Soporta STARTTLS y autenticación LOGIN.
 */
class Mailer
{
    private array $cfg;

    public function __construct()
    {
        $this->cfg = require ROOT_PATH . 'config/mail.php';
    }

    // ══════════════════════════════════════════════════════
    //  API PÚBLICA
    // ══════════════════════════════════════════════════════

    public function send(string $to, string $subject, string $bodyHtml, string $bodyText = ''): bool
    {
        $cfg       = $this->cfg;
        $uid       = md5(uniqid((string)time()));
        $bodyText  = $bodyText ?: strip_tags($bodyHtml);

        $headers = [
            "From: {$cfg['from_name']} <{$cfg['from_email']}>",
            "Reply-To: {$cfg['from_email']}",
            "To: {$to}",
            "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=",
            "MIME-Version: 1.0",
            "Content-Type: multipart/alternative; boundary=\"{$uid}\"",
            "X-Mailer: OneGantt/SMTP",
        ];

        $body  = "--{$uid}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n{$bodyText}\r\n";
        $body .= "--{$uid}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n\r\n{$bodyHtml}\r\n";
        $body .= "--{$uid}--";

        $payload = implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.";

        try {
            return $this->smtpExecute($to, $payload);
        } catch (Exception $e) {
            error_log("[OneGantt][Mailer] " . $e->getMessage());
            return false;
        }
    }

    // ── Plantillas ────────────────────────────────────────

    /**
     * Correo de asignación de tarea.
     * Se envía cuando se vincula un colaborador a una tarea.
     */
    public function sendTaskAssigned(array $usuario, array $task, array $asignador): bool
    {
        $subject = "📋 Nueva tarea asignada: \"{$task['titulo']}\"";

        $prioLabel = ['1' => 'Baja', '2' => 'Media', '3' => 'Alta'][$task['prioridad']] ?? 'Media';
        $prioColor = ['1' => '#10b981', '2' => '#f59e0b', '3' => '#ef4444'][$task['prioridad']] ?? '#f59e0b';
        $prioIcon  = ['1' => '🟢', '2' => '🟡', '3' => '🔴'][$task['prioridad']] ?? '🟡';

        $fechaFin  = !empty($task['fecha_fin'])
            ? date('d \d\e F, Y', strtotime($task['fecha_fin']))
            : 'Sin fecha límite';

        $taskUrl   = rtrim(BASE_URL, '/') . '/tasks/edit/' . $task['id'];
        $inicialAs = strtoupper(mb_substr($asignador['nombre'], 0, 1));
        $inicialUs = strtoupper(mb_substr($usuario['nombre'], 0, 1));

        $html = $this->wrapEmail("Nueva tarea asignada", "
          <!-- Avatar asignado por -->
          <div style='text-align:center;margin-bottom:28px'>
            <div style='display:inline-flex;align-items:center;justify-content:center;
                        width:52px;height:52px;border-radius:50%;
                        background:linear-gradient(135deg,#6366f1,#8b5cf6);
                        color:#fff;font-weight:700;font-size:22px;
                        box-shadow:0 4px 20px rgba(99,102,241,.4);
                        margin-bottom:12px'>
              {$inicialUs}
            </div>
            <h2 style='margin:0;font-size:22px;font-weight:800;
                       background:linear-gradient(135deg,#818cf8,#0ea5e9);
                       -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                       background-clip:text'>
              ¡Tienes una nueva tarea!
            </h2>
            <p style='color:#94a3b8;font-size:14px;margin:6px 0 0'>
              {$asignador['nombre']} te asignó la siguiente tarea
            </p>
          </div>

          <!-- Tarjeta de tarea -->
          <div style='background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.2);
                      border-radius:16px;padding:24px;margin-bottom:24px'>

            <!-- Título -->
            <h3 style='color:#e2e8f0;font-size:18px;font-weight:700;margin:0 0 16px;
                       line-height:1.4'>
              📋 {$task['titulo']}
            </h3>

            <!-- Descripción -->
            " . (!empty($task['descripcion']) ? "
            <p style='color:#94a3b8;font-size:14px;line-height:1.6;margin:0 0 20px;
                      background:rgba(0,0,0,.2);border-radius:8px;padding:12px 14px;
                      border-left:3px solid rgba(99,102,241,.5)'>
              " . htmlspecialchars(mb_strimwidth($task['descripcion'], 0, 200, '...')) . "
            </p>" : "") . "

            <!-- Meta grid -->
            <div style='display:grid;grid-template-columns:1fr 1fr;gap:12px'>

              <div style='background:rgba(0,0,0,.2);border-radius:10px;padding:12px 14px;
                          border:1px solid rgba(255,255,255,.06)'>
                <div style='font-size:10px;color:#64748b;text-transform:uppercase;
                            letter-spacing:1px;margin-bottom:5px;font-weight:700'>
                  Proyecto
                </div>
                <div style='color:#e2e8f0;font-size:13px;font-weight:600'>
                  📁 " . htmlspecialchars($task['proyecto_nombre'] ?? $task['proyecto'] ?? '—') . "
                </div>
              </div>

              <div style='background:rgba(0,0,0,.2);border-radius:10px;padding:12px 14px;
                          border:1px solid rgba(255,255,255,.06)'>
                <div style='font-size:10px;color:#64748b;text-transform:uppercase;
                            letter-spacing:1px;margin-bottom:5px;font-weight:700'>
                  Prioridad
                </div>
                <div style='color:{$prioColor};font-size:13px;font-weight:700'>
                  {$prioIcon} {$prioLabel}
                </div>
              </div>

              <div style='background:rgba(0,0,0,.2);border-radius:10px;padding:12px 14px;
                          border:1px solid rgba(255,255,255,.06)'>
                <div style='font-size:10px;color:#64748b;text-transform:uppercase;
                            letter-spacing:1px;margin-bottom:5px;font-weight:700'>
                  Fecha l&iacute;mite
                </div>
                <div style='color:#f59e0b;font-size:13px;font-weight:600'>
                  📅 {$fechaFin}
                </div>
              </div>

              <div style='background:rgba(0,0,0,.2);border-radius:10px;padding:12px 14px;
                          border:1px solid rgba(255,255,255,.06)'>
                <div style='font-size:10px;color:#64748b;text-transform:uppercase;
                            letter-spacing:1px;margin-bottom:5px;font-weight:700'>
                  Asignado por
                </div>
                <div style='display:flex;align-items:center;gap:6px'>
                  <div style='width:20px;height:20px;border-radius:50%;
                              background:linear-gradient(135deg,#6366f1,#8b5cf6);
                              display:inline-flex;align-items:center;justify-content:center;
                              color:#fff;font-size:10px;font-weight:700;flex-shrink:0'>
                    {$inicialAs}
                  </div>
                  <span style='color:#e2e8f0;font-size:13px;font-weight:600'>
                    " . htmlspecialchars($asignador['nombre']) . "
                  </span>
                </div>
              </div>

            </div>
          </div>

          <!-- CTA -->
          <div style='text-align:center'>
            <a href='{$taskUrl}'
               style='display:inline-block;
                      background:linear-gradient(135deg,#6366f1,#8b5cf6);
                      color:#fff;padding:14px 36px;border-radius:12px;
                      text-decoration:none;font-size:15px;font-weight:700;
                      box-shadow:0 6px 24px rgba(99,102,241,.4);
                      letter-spacing:-.2px'>
              Ver mi tarea &rarr;
            </a>
            <p style='color:#64748b;font-size:12px;margin-top:14px'>
              O copia este enlace: <a href='{$taskUrl}' style='color:#818cf8'>{$taskUrl}</a>
            </p>
          </div>
        ");

        return $this->send($usuario['email'], $subject, $html);
    }

    /**
     * Recordatorio de tarea próxima a vencer.
     */
    public function sendTaskReminder(array $user, array $task): bool
    {
        $subject  = "⏰ Recordatorio: \"{$task['titulo']}\" vence pronto";
        $fechaFin = !empty($task['fecha_fin'])
            ? date('d \d\e F, Y', strtotime($task['fecha_fin']))
            : '—';
        $taskUrl  = rtrim(BASE_URL, '/') . '/tasks/edit/' . $task['id'];

        $html = $this->wrapEmail("Recordatorio de tarea", "
          <div style='text-align:center;margin-bottom:28px'>
            <div style='font-size:48px;margin-bottom:12px'>⏰</div>
            <h2 style='margin:0;font-size:22px;font-weight:800;color:#f59e0b'>
              Tu tarea vence pronto
            </h2>
            <p style='color:#94a3b8;font-size:14px;margin:6px 0 0'>
              Aseg&uacute;rate de completarla a tiempo
            </p>
          </div>

          <div style='background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25);
                      border-radius:16px;padding:24px;margin-bottom:24px'>
            <h3 style='color:#e2e8f0;font-size:17px;font-weight:700;margin:0 0 12px'>
              {$task['titulo']}
            </h3>
            <div style='display:flex;gap:8px;flex-wrap:wrap'>
              <span style='background:rgba(245,158,11,.15);color:#f59e0b;
                           border:1px solid rgba(245,158,11,.3);padding:4px 12px;
                           border-radius:20px;font-size:12px;font-weight:700'>
                📅 Vence: {$fechaFin}
              </span>
              <span style='background:rgba(255,255,255,.05);color:#94a3b8;
                           border:1px solid rgba(255,255,255,.1);padding:4px 12px;
                           border-radius:20px;font-size:12px;font-weight:600'>
                📁 {$task['proyecto']}
              </span>
            </div>
          </div>

          <div style='text-align:center'>
            <a href='{$taskUrl}'
               style='display:inline-block;
                      background:linear-gradient(135deg,#f59e0b,#f97316);
                      color:#fff;padding:14px 36px;border-radius:12px;
                      text-decoration:none;font-size:15px;font-weight:700;
                      box-shadow:0 6px 24px rgba(245,158,11,.4)'>
              Ver tarea &rarr;
            </a>
          </div>
        ");

        try {
            return $this->send($user['email'], $subject, $html);
        } catch (Exception $e) {
            error_log("[OneGantt][Mailer] " . $e->getMessage());
            return false;
        }
    }

    // ══════════════════════════════════════════════════════
    //  WRAPPER DE EMAIL (Crystal Dark)
    // ══════════════════════════════════════════════════════

    private function wrapEmail(string $preheader, string $body): string
    {
        $year = date('Y');
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="color-scheme" content="dark">
  <title>{$preheader}</title>
  <!--[if mso]><noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript><![endif]-->
</head>
<body style='margin:0;padding:0;background:#060d1f;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif'>

  <!-- Preheader oculto -->
  <span style='display:none;max-height:0;overflow:hidden;opacity:0'>{$preheader} · OneGantt</span>

  <!-- Wrapper -->
  <table width='100%' cellpadding='0' cellspacing='0' style='background:#060d1f;min-height:100vh'>
    <tr>
      <td align='center' style='padding:40px 16px'>

        <!-- Tarjeta principal -->
        <table width='580' cellpadding='0' cellspacing='0' style='max-width:580px;width:100%'>

          <!-- Header de marca -->
          <tr>
            <td style='background:linear-gradient(135deg,#0f1f45,#0b1530);
                        border-radius:20px 20px 0 0;
                        border:1px solid rgba(99,102,241,.25);
                        border-bottom:none;
                        padding:24px 32px;
                        text-align:center'>
              <!-- Logo -->
              <div style='display:inline-flex;align-items:center;gap:10px;margin-bottom:4px'>
                <div style='width:36px;height:36px;border-radius:10px;
                            background:linear-gradient(135deg,#6366f1,#8b5cf6);
                            display:inline-flex;align-items:center;justify-content:center'>
                  <span style='font-size:18px'>▲</span>
                </div>
                <span style='font-size:20px;font-weight:800;
                             background:linear-gradient(135deg,#818cf8,#0ea5e9);
                             -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                             background-clip:text;letter-spacing:-.5px'>
                  OneGantt
                </span>
              </div>
              <div style='font-size:11px;color:#475569;text-transform:uppercase;
                          letter-spacing:2px;margin-top:4px'>
                Powered by Apotema Lab
              </div>
            </td>
          </tr>

          <!-- Cuerpo -->
          <tr>
            <td style='background:rgba(11,21,48,.95);
                        border:1px solid rgba(99,102,241,.15);
                        border-top:none;border-bottom:none;
                        padding:36px 32px'>
              {$body}
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style='background:rgba(6,13,31,.9);
                        border-radius:0 0 20px 20px;
                        border:1px solid rgba(99,102,241,.1);
                        border-top:none;
                        padding:20px 32px;
                        text-align:center'>
              <p style='color:#334155;font-size:12px;margin:0 0 6px'>
                &copy; {$year} OneGantt &mdash; Apotema Lab. Todos los derechos reservados.
              </p>
              <p style='color:#1e293b;font-size:11px;margin:0'>
                Este correo fue enviado autom&aacute;ticamente. No responder a este mensaje.
              </p>
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }

    // ══════════════════════════════════════════════════════
    //  MOTOR SMTP
    // ══════════════════════════════════════════════════════

    private function smtpExecute(string $to, string $payload): bool
    {
        $cfg  = $this->cfg;
        $sock = @fsockopen($cfg['host'], $cfg['port'], $errno, $errstr, 15);
        if (!$sock) {
            throw new Exception("SMTP: no se pudo conectar a {$cfg['host']}:{$cfg['port']} — {$errstr}");
        }

        $server = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $this->read($sock);
        $this->cmd($sock, "EHLO {$server}", 250);

        if ($cfg['encryption'] === 'tls') {
            $this->cmd($sock, "STARTTLS", 220);
            if (!stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_ANY_CLIENT)) {
                fclose($sock);
                throw new Exception("SMTP: fallo al activar TLS");
            }
            $this->cmd($sock, "EHLO {$server}", 250);
        }

        $this->cmd($sock, "AUTH LOGIN", 334);
        $this->cmd($sock, base64_encode($cfg['username']), 334);
        $this->cmd($sock, base64_encode($cfg['password']), 235);
        $this->cmd($sock, "MAIL FROM:<{$cfg['username']}>", 250);
        $this->cmd($sock, "RCPT TO:<{$to}>", 250);
        $this->cmd($sock, "DATA", 354);

        fwrite($sock, $payload . "\r\n");
        $this->read($sock, 250);

        fwrite($sock, "QUIT\r\n");
        fclose($sock);

        return true;
    }

    private function cmd($sock, string $cmd, int $expect): string
    {
        fwrite($sock, "{$cmd}\r\n");
        return $this->read($sock, $expect);
    }

    private function read($sock, int $expect = null): string
    {
        $resp = '';
        while ($line = fgets($sock, 512)) {
            $resp .= $line;
            if ($line[3] === ' ') break;
        }
        $code = (int)substr($resp, 0, 3);
        if ($expect && $code !== $expect) {
            throw new Exception("SMTP: esperaba {$expect}, recibí {$code}. Resp: {$resp}");
        }
        return $resp;
    }
}

<?php
// app/helpers/Email.php - Envío de correos con PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ROOT_PATH . 'app/helpers/phpmailer/PHPMailer.php';
require_once ROOT_PATH . 'app/helpers/phpmailer/SMTP.php';
require_once ROOT_PATH . 'app/helpers/phpmailer/Exception.php';

class EmailHelper
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configurar();
    }

    /**
     * Configurar PHPMailer
     */
    private function configurar()
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['SMTP_USER'] ?? '';
            $this->mail->Password = $_ENV['SMTP_PASS'] ?? '';
            $this->mail->SMTPSecure = $_ENV['SMTP_SECURE'] ?? PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 587);

            $this->mail->setFrom(
                $_ENV['MAIL_FROM'] ?? 'noreply@example.com',
                $_ENV['MAIL_FROM_NAME'] ?? 'EK Proveedores'
            );

            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);

        } catch (Exception $e) {
            error_log("Error al configurar PHPMailer: " . $e->getMessage());
        }
    }

    /**
     * Enviar email
     */
    public function enviar($destinatario, $asunto, $cuerpoHTML, $cc = [], $archivos = [])
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($destinatario);

            foreach ($cc as $correo_cc) {
                $this->mail->addCC($correo_cc);
            }

            foreach ($archivos as $archivo) {
                if (file_exists($archivo)) {
                    $this->mail->addAttachment($archivo);
                }
            }

            $this->mail->Subject = $asunto;
            $this->mail->Body = $cuerpoHTML;
            $this->mail->AltBody = strip_tags($cuerpoHTML);

            $resultado = $this->mail->send();

            if ($resultado) {
                logSeguridad('email_enviado', "Email enviado a: $destinatario - Asunto: $asunto", null, 'info');
            }

            return $resultado;

        } catch (Exception $e) {
            error_log("Error al enviar email: " . $this->mail->ErrorInfo);
            logSeguridad('email_error', "Error al enviar email a: $destinatario - Error: " . $this->mail->ErrorInfo, null, 'error');
            return false;
        }
    }

    /**
     * Plantilla de email base
     */
    private function plantillaBase($contenido, $titulo = '')
    {
        $app_name = APP_NAME;
        $app_url = BASE_URL;
        $year = date('Y');

        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$titulo}</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f5f7; }
                .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px 20px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
                .content { padding: 30px 20px; color: #2d3748; line-height: 1.6; }
                .footer { background: #f7fafc; padding: 20px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; }
                .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; margin: 10px 0; }
                .info-box { background: #ebf8ff; border-left: 4px solid #3182ce; padding: 15px; margin: 15px 0; border-radius: 4px; }
                .alert-box { background: #fef5e7; border-left: 4px solid #f39c12; padding: 15px; margin: 15px 0; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>{$app_name}</h1>
                </div>
                <div class='content'>
                    {$contenido}
                </div>
                <div class='footer'>
                    <p>© {$year} {$app_name} - Desarrollado por <strong>Apotema One</strong></p>
                    <p><a href='https://www.apotemaone.com' target='_blank' style='color: #667eea;'>www.apotemaone.com</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Email de nuevo proveedor registrado
     */
    public function nuevoProveedor($admin_email, $proveedor)
    {
        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>Nuevo Proveedor Registrado</h2>
            <p>Se ha registrado un nuevo proveedor en el sistema:</p>

            <div class='info-box'>
                <strong>RFC:</strong> {$proveedor['RFC']}<br>
                <strong>Razón Social:</strong> {$proveedor['RazonSocial']}<br>
                <strong>Responsable:</strong> {$proveedor['Responsable']}<br>
                <strong>Correo Proveedor:</strong> {$proveedor['CorreoProveedor']}
            </div>

            <p>Este proveedor requiere <strong>revisión y aprobación</strong>.</p>

            <a href='" . BASE_URL . "proveedores/editar/{$proveedor['Id']}' class='btn'>Ver Proveedor</a>
        ";

        $html = $this->plantillaBase($contenido, 'Nuevo Proveedor Registrado');

        return $this->enviar($admin_email, 'Nuevo Proveedor Registrado - ' . $proveedor['RFC'], $html);
    }

    /**
     * Email de solicitud de cambio
     */
    public function solicitudCambio($admin_email, $proveedor, $solicitante)
    {
        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>Nueva Solicitud de Cambio</h2>
            <p>El usuario <strong>{$solicitante}</strong> ha solicitado cambios para el proveedor:</p>

            <div class='info-box'>
                <strong>Proveedor:</strong> {$proveedor['RazonSocial']}<br>
                <strong>RFC:</strong> {$proveedor['RFC']}<br>
                <strong>Solicitante:</strong> {$solicitante}
            </div>

            <p>Por favor, revise y apruebe o rechace esta solicitud.</p>

            <a href='" . BASE_URL . "solicitudes' class='btn'>Ver Solicitudes Pendientes</a>
        ";

        $html = $this->plantillaBase($contenido, 'Nueva Solicitud de Cambio');

        return $this->enviar($admin_email, 'Nueva Solicitud de Cambio - ' . $proveedor['RFC'], $html);
    }

    /**
     * Email de solicitud aprobada
     */
    public function solicitudAprobada($proveedor_email, $proveedor)
    {
        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>Solicitud Aprobada</h2>
            <p>Su solicitud de cambios ha sido <strong style='color: #38a169;'>APROBADA</strong>.</p>

            <div class='info-box'>
                <strong>Proveedor:</strong> {$proveedor['RazonSocial']}<br>
                <strong>RFC:</strong> {$proveedor['RFC']}
            </div>

            <p>Los cambios ya se encuentran reflejados en el sistema.</p>
        ";

        $html = $this->plantillaBase($contenido, 'Solicitud Aprobada');

        return $this->enviar($proveedor_email, 'Su solicitud ha sido aprobada - ' . $proveedor['RFC'], $html);
    }

    /**
     * Email de solicitud rechazada
     */
    public function solicitudRechazada($proveedor_email, $proveedor, $motivo = '')
    {
        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>Solicitud Rechazada</h2>
            <p>Su solicitud de cambios ha sido <strong style='color: #e53e3e;'>RECHAZADA</strong>.</p>

            <div class='alert-box'>
                <strong>Proveedor:</strong> {$proveedor['RazonSocial']}<br>
                <strong>RFC:</strong> {$proveedor['RFC']}
            </div>

            " . ($motivo ? "<p><strong>Motivo:</strong><br>{$motivo}</p>" : "") . "

            <p>Si tiene dudas, por favor contacte al administrador.</p>
        ";

        $html = $this->plantillaBase($contenido, 'Solicitud Rechazada');

        return $this->enviar($proveedor_email, 'Su solicitud ha sido rechazada - ' . $proveedor['RFC'], $html);
    }

    /**
     * Email de recuperación de contraseña
     */
    public function recuperarPassword($email, $nombre, $token)
    {
        $url_reset = BASE_URL . "recuperar-password?token=" . $token;

        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>Recuperación de Contraseña</h2>
            <p>Hola <strong>{$nombre}</strong>,</p>
            <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>

            <div class='info-box'>
                <p>Haz clic en el siguiente botón para crear una nueva contraseña:</p>
                <a href='{$url_reset}' class='btn'>Restablecer Contraseña</a>
            </div>

            <p style='font-size: 14px; color: #718096;'>
                Este enlace expirará en 1 hora.<br>
                Si no solicitaste este cambio, ignora este mensaje.
            </p>
        ";

        $html = $this->plantillaBase($contenido, 'Recuperación de Contraseña');

        return $this->enviar($email, 'Recuperación de Contraseña - ' . APP_NAME, $html);
    }

    /**
     * Email de bienvenida
     */
    public function bienvenida($email, $nombre, $username, $password_temporal)
    {
        $url_login = BASE_URL . "login";

        $contenido = "
            <h2 style='color: #2d3748; margin-top: 0;'>¡Bienvenido a " . APP_NAME . "!</h2>
            <p>Hola <strong>{$nombre}</strong>,</p>
            <p>Tu cuenta ha sido creada exitosamente.</p>

            <div class='info-box'>
                <strong>Usuario:</strong> {$username}<br>
                <strong>Contraseña Temporal:</strong> <code style='background: #f7fafc; padding: 2px 6px; border-radius: 3px;'>{$password_temporal}</code>
            </div>

            <div class='alert-box'>
                <strong>⚠️ Importante:</strong> Deberás cambiar tu contraseña en el primer inicio de sesión.
            </div>

            <a href='{$url_login}' class='btn'>Iniciar Sesión</a>
        ";

        $html = $this->plantillaBase($contenido, 'Bienvenido');

        return $this->enviar($email, 'Bienvenido a ' . APP_NAME, $html);
    }
}

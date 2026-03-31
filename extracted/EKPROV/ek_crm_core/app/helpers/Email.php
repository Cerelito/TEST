<?php
// app/helpers/Email.php

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

    private function configurar()
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $_ENV['SMTP_USER'];
            $this->mail->Password = $_ENV['SMTP_PASS'];
            $this->mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $this->mail->Port = (int) $_ENV['SMTP_PORT'];
            $this->mail->setFrom($_ENV['SMTP_USER'], 'Dublín EkProv');
            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);
        } catch (Exception $e) {
            error_log("Error config email: " . $e->getMessage());
        }
    }

    public function enviar($destinatario, $asunto, $cuerpoHTML, $cc = [])
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearCCs();
            $this->mail->addAddress($destinatario);

            if (!empty($cc)) {
                foreach ($cc as $correo) {
                    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                        $this->mail->addCC($correo);
                    }
                }
            }

            $this->mail->Subject = $asunto;
            $this->mail->Body = $cuerpoHTML;
            $this->mail->AltBody = strip_tags($cuerpoHTML);
            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Fallo envío: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    private function renderPlantilla($titulo, $contenido, $color_borde = '#3b82f6')
    {
        $anio = date('Y');
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; color: #1f2937; }
                .wrapper { width: 100%; table-layout: fixed; background-color: #f3f4f6; padding-bottom: 40px; }
                .main-box { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
                .header { background-color: #0f172a; padding: 30px 40px; text-align: center; border-bottom: 4px solid $color_borde; }
                .header h1 { margin: 0; color: #ffffff; font-size: 24px; letter-spacing: 1px; font-weight: 800; text-transform: uppercase; }
                .content { padding: 40px; }
                .title-section { text-align: center; margin-bottom: 30px; }
                .title-section h2 { margin: 0; color: #111827; font-size: 20px; font-weight: 700; }
                .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #f8fafc; border-radius: 8px; overflow: hidden; }
                .data-table td { padding: 12px 15px; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
                .data-table td:last-child { border-bottom: none; }
                .data-label { font-weight: 600; color: #64748b; width: 40%; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
                .data-value { color: #0f172a; font-weight: 500; }
                .message-box { margin-top: 25px; padding: 15px 20px; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px; color: #1e40af; font-size: 14px; line-height: 1.5; }
                .footer { background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; }
                .signature { margin-top: 30px; font-weight: 700; color: #0f172a; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
            </style>
        </head>
        <body>
            <div class='wrapper'>
                <br>
                <div class='main-box'>
                    <div class='header'>
                        <h1>Dublín EkProv</h1>
                    </div>
                    <div class='content'>
                        <div class='title-section'>
                            <h2>$titulo</h2>
                        </div>
                        $contenido
                        <div class='signature'>
                            Att. Dublín EkProv
                        </div>
                    </div>
                    <div class='footer'>
                        &copy; $anio Dublín EkProv Sistema de Gestión de Proveedores.<br>
                        Mensaje generado automáticamente.
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }

    private function fila($label, $valor)
    {
        if ($valor === null || $valor === '')
            return '';

        if ($label === "Límite de Crédito") {
            $valor = "$" . number_format((float) str_replace(['$', ','], '', $valor), 2);
        }

        return "<tr><td class='data-label'>$label</td><td class='data-value'>$valor</td></tr>";
    }

    /**
     * FICHA TÉCNICA COMPLETA (DESTACADA)
     */
    public function solicitudAprobada($destinatario, $proveedor, $cuentasBancarias, $cc = [])
    {
        // BLOQUE 1: IDENTIFICACIÓN DESTACADA (LO QUE VA PRIMERO)
        $identidadHtml = "
        <div style='background-color: #f8fafc; border: 2px solid #3b82f6; border-radius: 12px; padding: 25px; margin-bottom: 30px; text-align: center;'>
            <span style='display: block; font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 1.5px; margin-bottom: 5px;'>ID INTERNO DEL SISTEMA</span>
            <strong style='display: block; font-size: 32px; color: #3b82f6; margin-bottom: 10px;'>" . ($proveedor['IdManual'] ?: 'NUEVO') . "</strong>
            <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 15px 0;'>
            <strong style='display: block; font-size: 20px; color: #0f172a;'>" . e($proveedor['RazonSocial'] ?: ($proveedor['Nombre'] . ' ' . $proveedor['ApellidoPaterno'])) . "</strong>
            <span style='display: block; color: #64748b; font-family: monospace; font-size: 16px; margin-top: 5px;'>RFC: " . e($proveedor['RFC']) . "</span>
        </div>";

        $direccion = e($proveedor['Calle']) . " #" . e($proveedor['NumeroExterior']);
        if (!empty($proveedor['NumeroInterior']))
            $direccion .= " Int. " . e($proveedor['NumeroInterior']);
        $direccion .= ", Col. " . e($proveedor['Colonia']) . ", CP. " . e($proveedor['CP']) . ", " . e($proveedor['Municipio']) . ", " . e($proveedor['Estado']);

        $tablasInfo = "
        <h3 style='font-size: 14px; color: #1e40af; text-transform: uppercase; margin-bottom: 10px; border-left: 4px solid #3b82f6; padding-left: 10px;'>1. Clasificación y Domicilio</h3>
        <table class='data-table' style='margin-bottom: 25px;'>
            " . $this->fila("Tipo Proveedor", $proveedor['TipoProveedor']) . "
            " . $this->fila("Régimen Fiscal", $proveedor['regimen_nombre'] ?: $proveedor['RegimenFiscalId']) . "
            " . $this->fila("Dirección Fiscal", $direccion) . "
        </table>

        <h3 style='font-size: 14px; color: #1e40af; text-transform: uppercase; margin-bottom: 10px; border-left: 4px solid #3b82f6; padding-left: 10px;'>2. Contacto y Crédito</h3>
        <table class='data-table' style='margin-bottom: 25px;'>
            " . $this->fila("Responsable", $proveedor['Responsable']) . "
            " . $this->fila("Email Ventas", $proveedor['CorreoProveedor']) . "
            " . $this->fila("Email ", $proveedor['CorreoPagosInterno']) . "
            " . $this->fila("Límite de Crédito", $proveedor['LimiteCredito']) . "
            " . $this->fila("Empresas", $proveedor['cias_nombres']) . "
        </table>";

        $bancosHtml = "";
        if (!empty($cuentasBancarias)) {
            $bancosHtml .= "<h3 style='font-size: 14px; color: #1e40af; text-transform: uppercase; margin-bottom: 10px; border-left: 4px solid #3b82f6; padding-left: 10px;'>3. Información Bancaria</h3>";

            // AGRUPAR POR CUENTA/CLABE
            $cuentasAgrupadas = [];
            foreach ($cuentasBancarias as $cta) {
                $uniqueKey = !empty($cta['Clabe']) ? $cta['Clabe'] : $cta['Cuenta'];
                if (!isset($cuentasAgrupadas[$uniqueKey])) {
                    $cuentasAgrupadas[$uniqueKey] = [
                        'BancoNombre' => $cta['BancoNombre'],
                        'Cuenta' => $cta['Cuenta'],
                        'Clabe' => $cta['Clabe'],
                        'EsPrincipal' => $cta['EsPrincipal'],
                        'Compañias' => []
                    ];
                }
                $cuentasAgrupadas[$uniqueKey]['Compañias'][] = $cta['CiaNombre'];
                if ($cta['EsPrincipal'])
                    $cuentasAgrupadas[$uniqueKey]['EsPrincipal'] = true;
            }

            foreach ($cuentasAgrupadas as $key => $cta) {
                $principalBadge = ($cta['EsPrincipal']) ? "<span style='background:#ef4444; color:#ffffff; font-size:10px; padding:3px 10px; border-radius:10px; margin-left:10px; font-weight:800;'>PRINCIPAL</span>" : "";
                $ciasList = implode(', ', array_unique($cta['Compañias']));

                $bancosHtml .= "
                <div style='background: #ffffff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; margin-bottom: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.04);'>
                    <div style='font-weight: 700; color: #0f172a; font-size: 15px; margin-bottom: 8px;'>
                        " . e($cta['BancoNombre']) . " $principalBadge
                    </div>
                    <div style='font-size: 13px; color: #475569; line-height: 1.6;'>
                        " . (!empty($cta['Cuenta']) ? "<strong>CUENTA:</strong> <span style='font-family: monospace;'>" . e($cta['Cuenta']) . "</span><br>" : "") . "
                        <strong>CLABE:</strong> <span style='font-family: monospace;'>" . e($cta['Clabe']) . "</span>
                    </div>
                    <div style='margin-top: 8px; color: #ef4444; font-size: 11px; font-weight: 700; text-transform: uppercase;'>
                        EMPRESAS: " . e($ciasList) . "
                    </div>
                </div>";
            }
        }

        $finalHtml = "
            <p style='text-align: center; color: #475569; font-size: 15px; margin-bottom: 25px;'>Se ha generado la ficha técnica oficial en Dublín EkProv con el expediente completo:</p>
            $identidadHtml
            $tablasInfo
            $bancosHtml
            <div class='message-box' style='background-color: #f0fdf4; border-left-color: #22c55e; color: #15803d; margin-top: 30px; text-align:center;'>
                <strong>EXPEDIENTE COMPLETADO</strong><br>
                El proveedor está habilitado para operaciones y programación de pagos.
            </div>";

        $cuerpo = $this->renderPlantilla("Ficha Técnica del Proveedor", $finalHtml, '#22c55e');
        return $this->enviar($destinatario, "FICHA TÉCNICA: " . $proveedor['RFC'], $cuerpo, $cc);
    }

    public function solicitudAltaProceso($destinatario, $datos, $ciasNombres, $cc = [])
    {
        $tabla = "<table class='data-table'>";
        $tabla .= $this->fila("RFC", $datos['RFC']);
        $tabla .= $this->fila("Razón Social", $datos['RazonSocial'] ?: ($datos['Nombre'] . ' ' . $datos['ApellidoPaterno']));
        $tabla .= $this->fila("Empresas", $ciasNombres);
        $tabla .= "</table>";

        $html = "
            <p style='text-align: center; color: #475569;'>Hola, hemos recibido una nueva solicitud de alta en Dublín EkProv.</p>
            $tabla
            <div class='message-box'>
                <strong>REVISIÓN EN CURSO</strong><br>
                El área administrativa está validando los documentos. Se enviará una notificación al finalizar el proceso.
            </div>";

        $cuerpo = $this->renderPlantilla("Solicitud de Alta", $html, '#3b82f6');
        return $this->enviar($destinatario, "Alta en Proceso: " . $datos['RFC'], $cuerpo, $cc);
    }

    public function solicitudCambio($destinatario, $proveedor, $cambiosDetalle, $cc = [])
    {
        $tabla = "<table class='data-table'>";
        $tabla .= $this->fila("ID Interno", $proveedor['IdManual'] ?? 'N/A');
        $tabla .= $this->fila("Proveedor", $proveedor['RazonSocial']);
        $tabla .= $this->fila("RFC", $proveedor['RFC']);
        $tabla .= "</table>";

        $html = "
            <p style='text-align: center; color: #475569;'>Se ha registrado una solicitud de modificación en Dublín EkProv.</p>
            $tabla
            <p style='margin-top: 20px; font-weight: 600; font-size: 14px; color: #334155;'>DETALLES:</p>
            <div style='background: #fff; border: 1px dashed #cbd5e1; padding: 15px; border-radius: 8px; font-size: 13px; color: #475569;'>
                $cambiosDetalle
            </div>";

        $cuerpo = $this->renderPlantilla("Cambio Solicitado", $html, '#f59e0b');
        return $this->enviar($destinatario, "Cambio Solicitado: " . $proveedor['RazonSocial'], $cuerpo, $cc);
    }

    public function solicitudRechazada($destinatario, $datos, $motivo, $ciasNombres = '', $cc = [])
    {
        $tabla = "<table class='data-table'>";
        $tabla .= $this->fila("RFC", $datos['RFC']);
        $tabla .= $this->fila("Proveedor", $datos['RazonSocial']);
        if (!empty($ciasNombres)) {
            $tabla .= $this->fila("Compañías", $ciasNombres);
        }
        $tabla .= "</table>";

        $html = "
            <p style='text-align: center; color: #475569;'>La solicitud enviada a Dublín EkProv no pudo ser aprobada en este momento.</p>
            $tabla
            <div class='message-box' style='background-color: #fef2f2; border-left-color: #ef4444; color: #991b1b;'>
                <strong>SOLICITUD RECHAZADA</strong><br>
                Por favor, verifique los datos adjuntos o contacte al administrador para más detalles.
            </div>";

        $cuerpo = $this->renderPlantilla("Solicitud Rechazada", $html, '#ef4444');
        return $this->enviar($destinatario, "Rechazo: " . $datos['RazonSocial'], $cuerpo, $cc);
    }

    public function recuperarPassword($email, $nombre, $token)
    {
        return true;
    }
}
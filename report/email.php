}}<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoReserva(array $reserva, array $usuario): bool {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV[$MAIL_USER];
        $mail->Password   = $_ENV[$MAIL_PASS];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('homfort.hotel@gmail.com', 'HOMFORT Hotel');
        $mail->addAddress($usuario['email'], $usuario['name'] . ' ' . $usuario['last_name']);

        $ini    = new DateTime($reserva['fecha_inicio']);
        $fin    = new DateTime($reserva['fecha_final']);
        $noches = max(1, $ini->diff($fin)->days);

        $mail->Subject = 'Confirmación de tu Reserva — HOMFORT';

        $filaPeticiones = '';
        if (!empty($reserva['Descripcion'])) {
            $filaPeticiones = '
              <tr style="background:#0f172a;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #1e293b;">Peticiones especiales</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #1e293b;">'
                    . htmlspecialchars($reserva['Descripcion']) .
                '</td>
              </tr>';
        }

        $mail->isHTML(true);
        $mail->Body = '
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0f172a;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#0f172a;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0"
             style="background:#1e293b;border-radius:16px;overflow:hidden;border:1px solid #334155;">

        <tr>
          <td style="background:#d97706;padding:32px 40px;text-align:center;">
            <h1 style="margin:0;color:#fff;font-size:28px;font-weight:900;letter-spacing:4px;">HOMFORT</h1>
            <p style="margin:8px 0 0;color:#fef3c7;font-size:13px;letter-spacing:2px;text-transform:uppercase;">
              Confirmación de Reserva
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding:32px 40px 16px;">
            <p style="margin:0;color:#94a3b8;font-size:14px;">
              Hola, <strong style="color:#f8fafc;">'
                . htmlspecialchars($usuario['name']) . ' '
                . htmlspecialchars($usuario['last_name']) .
              '</strong>
            </p>
            <p style="margin:12px 0 0;color:#94a3b8;font-size:14px;line-height:1.6;">
              Tu reserva ha sido confirmada exitosamente. A continuación encontrarás todos los detalles de tu estancia.
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding:0 40px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2" style="background:#d97706;padding:10px 16px;border-radius:8px 8px 0 0;">
                  <span style="color:#fff;font-weight:bold;font-size:13px;text-transform:uppercase;letter-spacing:1px;">
                    Detalles de la Estancia
                  </span>
                </td>
              </tr>
              <tr style="background:#0f172a;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #1e293b;width:45%;">Habitación</td>
                <td style="padding:12px 16px;color:#f8fafc;font-weight:bold;font-size:13px;border-bottom:1px solid #1e293b;">
                  Hab. ' . htmlspecialchars($reserva['num_habitacion']) . ' — ' . htmlspecialchars($reserva['tipo_habitacion']) . '
                </td>
              </tr>
              <tr style="background:#1e293b;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #0f172a;">Fecha de Entrada</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #0f172a;">
                  ' . date('d/m/Y', strtotime($reserva['fecha_inicio'])) . '
                </td>
              </tr>
              <tr style="background:#0f172a;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #1e293b;">Fecha de Salida</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #1e293b;">
                  ' . date('d/m/Y', strtotime($reserva['fecha_final'])) . '
                </td>
              </tr>
              <tr style="background:#1e293b;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #0f172a;">Noches</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #0f172a;">
                  ' . $noches . ' noche(s)
                </td>
              </tr>
              <tr style="background:#0f172a;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #1e293b;">Personas</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #1e293b;">
                  ' . intval($reserva['num_personas']) . '
                </td>
              </tr>
              <tr style="background:#1e293b;">
                <td style="padding:12px 16px;color:#94a3b8;font-size:13px;border-bottom:1px solid #0f172a;">Método de Pago</td>
                <td style="padding:12px 16px;color:#f8fafc;font-size:13px;border-bottom:1px solid #0f172a;">
                  ' . htmlspecialchars($reserva['metodo_pago']) . '
                </td>
              </tr>
              ' . $filaPeticiones . '
            </table>
          </td>
        </tr>

        <tr>
          <td style="padding:0 40px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#d97706;border-radius:12px;">
              <tr>
                <td style="padding:20px 24px;">
                  <span style="color:#fef3c7;font-size:12px;text-transform:uppercase;letter-spacing:2px;">Total de tu Estancia</span>
                  <p style="margin:4px 0 0;color:#fff;font-size:32px;font-weight:900;">
                    $' . number_format($reserva['precio'], 0, ',', '.') . '
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td style="padding:0 40px 32px;">
            <div style="background:#0f172a;border-left:3px solid #d97706;border-radius:0 8px 8px 0;padding:16px 20px;">
              <p style="margin:0;color:#94a3b8;font-size:13px;line-height:1.6;">
                Presenta este correo al momento del <strong style="color:#f8fafc;">check-in</strong>.<br>
                Para modificaciones o cancelaciones, ingresa a
                <strong style="color:#d97706;">Mis Reservas</strong> en nuestra plataforma.
              </p>
            </div>
          </td>
        </tr>

        <tr>
          <td style="background:#0f172a;padding:24px 40px;text-align:center;border-top:1px solid #334155;">
            <p style="margin:0;color:#475569;font-size:12px;">© 2026 HOMFORT — Todos los derechos reservados</p>
            <p style="margin:6px 0 0;color:#475569;font-size:12px;">reservas@homfort.com | +57 300 000 0000</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>';

        $mail->AltBody =
            "HOMFORT — Confirmación de Reserva\n\n" .
            "Hola " . $usuario['name'] . " " . $usuario['last_name'] . ",\n\n" .
            "Tu reserva ha sido confirmada exitosamente.\n\n" .
            "Habitación:     Hab. " . $reserva['num_habitacion'] . " — " . $reserva['tipo_habitacion'] . "\n" .
            "Fecha entrada:  " . date('d/m/Y', strtotime($reserva['fecha_inicio'])) . "\n" .
            "Fecha salida:   " . date('d/m/Y', strtotime($reserva['fecha_final'])) . "\n" .
            "Noches:         " . $noches . "\n" .
            "Personas:       " . $reserva['num_personas'] . "\n" .
            "Método de pago: " . $reserva['metodo_pago'] . "\n" .
            "Total:          $" . number_format($reserva['precio'], 0, ',', '.') . "\n\n" .
            "Presenta este correo al check-in.\n\n" .
            "HOMFORT © 2026 — reservas@homfort.com";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Error enviando correo: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
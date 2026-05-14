<?php
class EmailController {
    public function enviarEmail() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . SITE_URL . 'index.php?action=getFormLoginUser');
            exit;
        }
        $id_reserva = intval($_POST['id'] ?? 0);
        $user_id    = $_SESSION['user_id'];

        $emailModel = new EmailModel();
        $reserva    = $emailModel->getDatosParaEmail($id_reserva, $user_id);

        $userModel = new User();
        $usuario   = $userModel->getUserById($user_id);

        if ($reserva && $usuario) {
            require_once 'report/email.php';
            $enviado = enviarCorreoReserva($reserva, $usuario);

            if ($enviado) {
                $_SESSION['success'] = 'Correo de confirmación enviado a ' . $usuario['email'] . '.';
            } else {
                $_SESSION['errores'] = ['mail' => 'No se pudo enviar el correo. Intenta de nuevo.'];
            }
        } else {
            $_SESSION['errores'] = ['mail' => 'Reserva no encontrada.'];
        }

        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }
}
?>

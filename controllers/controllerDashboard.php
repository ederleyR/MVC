<?php
class ControllerDashboard {

    public function validarReserva($datos) {
        $errors = [];

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $errors['usuario'] = "Debes iniciar sesión para realizar una reserva.";
        }

        $fecha_inicio = trim($datos['fecha_inicio'] ?? '');
        $fecha_fin    = trim($datos['fecha_fin']    ?? '');

        if (empty($fecha_inicio) || empty($fecha_fin)) {
            $errors['fecha'] = 'Ambas fechas son requeridas.';
        } else {
            $hoy = new DateTime('today');
            $ini = new DateTime($fecha_inicio);
            $fin = new DateTime($fecha_fin);

            if ($ini < $hoy) {
                $errors['fecha'] = 'La fecha de inicio no puede ser en el pasado.';
            } elseif ($ini >= $fin) {
                $errors['fecha'] = 'La fecha de fin debe ser posterior a la de inicio.';
            }
        }

        if (empty($datos['room_id'])) {
            $errors['habitacion'] = 'Debes seleccionar una habitación.';
        }

        $personas = intval($datos['num_personas'] ?? 0);
        if ($personas <= 0) {
            $errors['personas'] = 'El número de personas debe ser mayor a 0.';
        } elseif ($personas > 5) {
            $errors['personas'] = 'El máximo permitido son 5 personas.';
        }

        if (empty($datos['id_pago'])) {
            $errors['pago'] = 'Debes seleccionar un método de pago.';
        }

        return $errors;
    }
    private function calcularPrecioTotal($datos, $precioHabitacion) {
        $inicio  = new DateTime($datos['fecha_inicio']);
        $fin     = new DateTime($datos['fecha_fin']);
        $noches  = max(1, $inicio->diff($fin)->days);

        $numPersonas  = intval($datos['num_personas']);
        $cargoPersonas = ($numPersonas >= 2) ? ($numPersonas - 1) * 10000 : 0;

        return [
            'total'      => ($precioHabitacion +$cargoPersonas) *$noches ,
            'noches'     => $noches,
            'cargoExtra' => $cargoPersonas
        ];
    }
    public function mostrarFormularioReserva() {
        $reservaModel = new Reserva();
        $datosForm    = $reservaModel->getDataForm();
        require_once 'views/dashboard/reserve.php';
    }

    public function guardandoReserva() {
        $datos            = $_POST;
        $datos['user_id'] = $_SESSION['user_id'];

        $errores = $this->validarReserva($datos);
        if (count($errores) > 0) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old']     = $datos;
            header('Location: ' . SITE_URL . 'index.php?action=getFormReserveUser');
            exit;
        }

        $reservaModel = new Reserva();
        $habitacion   = $reservaModel->getHabitacionById($datos['room_id']);


        $calculo = $this->calcularPrecioTotal($datos, $habitacion['precio']);
        $datos['precio_total'] = $calculo['total'];

        $resultado = $reservaModel->registerReserve($datos);

        if ($resultado) {
            $userModel        = new User();
            $emailModel       = new EmailModel();
            $usuario          = $userModel->getUserById($_SESSION['user_id']);
            $reservaParaEmail = $emailModel->getDatosParaEmail(
                $reservaModel->getUltimaIdByUser($_SESSION['user_id']),
                $_SESSION['user_id']
            );
            if ($usuario && $reservaParaEmail) {
                require_once 'report/email.php';
                enviarCorreoReserva($reservaParaEmail, $usuario);
            }
            
            $_SESSION['success'] = '¡Reserva confirmada! Se ha enviado un correo de confirmación.';
            header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        } else {
            $_SESSION['errores'] = ['db' => 'Error al guardar en la base de datos.'];
            header('Location: ' . SITE_URL . 'index.php?action=getFormReserveUser');
        }
        exit;
    }

    public function mostrarMisReservas() {
        $success = $_SESSION['success'] ?? '';
        $errores = $_SESSION['errores'] ?? [];
        unset($_SESSION['success'], $_SESSION['errores']);
        $reservaModel = new Reserva();
        $reservas     = $reservaModel->getReservasByUser($_SESSION['user_id']);
        require_once 'views/dashboard/my_reserves.php';
    }

    public function mostrarFormularioEditar() {

        $id_reserva = intval($_POST['id'] ?? 0);
        $reservaModel = new Reserva();
        $reserva      = $reservaModel->getReservaById($id_reserva, $_SESSION['user_id']);

        if (!$reserva) {
            $_SESSION['errores'] = ['general' => 'Reserva no encontrada.'];
            header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
            exit;
        }

        $datosForm = $reservaModel->getDataForm();
        require_once 'views/dashboard/edit_reserve.php';
    }

    public function actualizarReserva() {

        $datos= $_POST;
        $datos['user_id'] = $_SESSION['user_id'];

        $errores = $this->validarReserva($datos);
        if (count($errores) > 0) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old']     = $datos;
            header('Location: ' . SITE_URL . 'index.php?action=getFormEditarReserva&id=' . intval($datos['id_reserva']));
            exit;
        }

        $reservaModel = new Reserva();
        $habitacion   = $reservaModel->getHabitacionById($datos['room_id']);
        $calculo              = $this->calcularPrecioTotal($datos, $habitacion['precio']);
        $datos['precio_total'] = $calculo['total'];

        $resultado = $reservaModel->updateReserva($datos, $_SESSION['user_id']);

        if ($resultado) {
            $_SESSION['success'] = "Reserva actualizada correctamente.";
        } else {
            $_SESSION['errores'] = ['db' => 'No se pudo actualizar la reserva.'];
        }
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }
    public function eliminarReserva() {

        $id_reserva   = intval($_POST['id_reserva'] ?? 0);
        $reservaModel = new Reserva();
        $resultado    = $reservaModel->deleteReserva($id_reserva, $_SESSION['user_id']);

        if ($resultado) {
            $_SESSION['success'] = "Reserva eliminada correctamente.";
        } else {
            $_SESSION['errores'] = ['general' => 'No se pudo eliminar la reserva.'];
        }
        header('Location: ' . SITE_URL . 'index.php?action=getMisReservas');
        exit;
    }
    
    public function AJAXCategoria() {
        header('Content-Type: application/json; charset=utf-8');

        $id_categoria = isset($_GET['id_categoria']) ? (int) $_GET['id_categoria'] : 0;
        $habitaciones = [];

        try {
            $conexion = new Conexion();
            $db = $conexion->conectar();

            $sql = "SELECT h.id, h.num_habitacion, t.name, h.precio
                    FROM habitaciones h
                    JOIN type_rooms t ON h.id_categoria = t.id
                    WHERE h.id_estado = 1 AND h.id_categoria = ?";

            $stmt = $db->prepare($sql);
            if (!$stmt) {
                throw new Exception('No se pudo preparar la consulta');
            }

            $stmt->bind_param('i', $id_categoria);
            $stmt->execute();
            $resultado = $stmt->get_result();

            while ($fila = $resultado->fetch_assoc()) {
                $habitaciones[] = $fila;
            }

            $stmt->close();
            $conexion->desconectar();

            echo json_encode($habitaciones);

        } catch (Exception $e) {
            echo json_encode([]);
        }
    }
}
?>

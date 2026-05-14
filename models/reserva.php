<?php
class Reserva {

    public function registerReserve($datos) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "INSERT INTO reservas (id_user, id_habitacion, fecha_inicio, fecha_final, num_personas, Descripcion, precio, id_metodo_pago, id_estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        if (!$stmt) die("Error en el prepare: " . $db->error);

        $id_user   = intval($_SESSION['user_id']);
        $id_estado = 1;

        $stmt->bind_param("iissisdii",
            $id_user, $datos['room_id'], $datos['fecha_inicio'],
            $datos['fecha_fin'], $datos['num_personas'],
            $datos['descripcion'], $datos['precio_total'],
            $datos['id_pago'], $id_estado
        );

        $resultado = $stmt->execute();
        $stmt->close(); 
        $conexion->desconectar();
        return $resultado;
    }

    public function getReservasByUser($user_id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "SELECT r.id, r.fecha_inicio, r.fecha_final, r.num_personas,
                       r.Descripcion, r.precio, r.id_estado,
                       h.num_habitacion, t.name AS tipo_habitacion,
                       mp.nombre AS metodo_pago
                FROM reservas r
                JOIN habitaciones  h  ON r.id_habitacion  = h.id
                JOIN type_rooms    t  ON h.id_categoria   = t.id
                JOIN Metodos_pagos mp ON r.id_metodo_pago = mp.id
                WHERE r.id_user = ?
                ORDER BY r.fecha_inicio DESC";

        $stmt = $db->prepare($sql);
        if (!$stmt) die("Error: " . $db->error);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $reservas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->desconectar();
        return $reservas;
    }

    public function getReservaById($id_reserva, $user_id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "SELECT r.*, h.num_habitacion, h.id_categoria
                FROM reservas r
                JOIN habitaciones h ON r.id_habitacion = h.id
                WHERE r.id = ? AND r.id_user = ?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $id_reserva, $user_id);
        $stmt->execute();
        $reserva = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
        return $reserva;
    }

    public function updateReserva($datos, $user_id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "UPDATE reservas
                SET id_habitacion = ?, fecha_inicio = ?, fecha_final = ?,
                    num_personas = ?, Descripcion = ?, precio = ?, id_metodo_pago = ?
                WHERE id = ? AND id_user = ?";

        $stmt = $db->prepare($sql);
        if (!$stmt) die("Error prepare updateReserva: " . $db->error);

        $stmt->bind_param("isssidiii",
            $datos['room_id'], $datos['fecha_inicio'], $datos['fecha_fin'],
            $datos['num_personas'], $datos['descripcion'],
            $datos['precio_total'], $datos['id_pago'],
            $datos['id_reserva'], $user_id
        );

        $resultado = $stmt->execute();
        $affected  = $db->affected_rows;
        $stmt->close();
        $conexion->desconectar();
        return $affected >= 0 && $resultado;
    }

    public function deleteReserva($id_reserva, $user_id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "DELETE FROM reservas WHERE id = ? AND id_user = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) die("Error prepare deleteReserva: " . $db->error);
        $stmt->bind_param("ii", $id_reserva, $user_id);
        $stmt->execute();
        $affected = $db->affected_rows;
        $stmt->close();
        $conexion->desconectar();
        return $affected > 0;
    }

    public function getDataForm() {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $consultas = [
            'availableRooms'     => "SELECT h.id, h.num_habitacion, t.name, h.precio, h.id_categoria 
                                    FROM habitaciones h 
                                    JOIN type_rooms t ON h.id_categoria = t.id 
                                    WHERE h.id_estado = 1",
            'availableCategoria' => "SELECT id, name FROM type_rooms",
            'metodosPago'        => "SELECT id, nombre FROM Metodos_pagos"
        ];

        $data = [];
        foreach ($consultas as $key => $sql) {
            $conexion->query($sql);
            $result = $conexion->getResult();
            $data[$key] = ($result) ? $result->fetch_all(MYSQLI_ASSOC) : [];
            $_SESSION[$key] = $data[$key];
        }
        $conexion->desconectar();
        return $data;
    }

    public function getHabitacionesByCategoria($id_categoria) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "SELECT h.id, h.num_habitacion, t.name, h.precio
                FROM habitaciones h
                JOIN type_rooms t ON h.id_categoria = t.id
                WHERE h.id_estado = 1 AND h.id_categoria = ?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id_categoria);
        $stmt->execute();
        $habitaciones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->desconectar();
        return $habitaciones;
    }

    public function getHabitacionById($id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql = "SELECT precio FROM habitaciones WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $habitacion = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
        return $habitacion;
    }
    public function getUltimaIdByUser($user_id) {
        $conexion = new Conexion();
        $db       = $conexion->conectar();

        $sql  = "SELECT id FROM reservas WHERE id_user = ? ORDER BY id DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
        return $fila['id'] ?? null;
    }
}
?>
    

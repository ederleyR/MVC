<?php 
    class PDF{
       public function getReservaPDF($id_reserva, $user_id) {
            $conexion = new Conexion();
            $db = $conexion->conectar();

            $sql = "SELECT r.id, r.fecha_inicio, r.fecha_final, r.num_personas,
                            r.Descripcion, r.precio, r.id_estado, r.created_at,
                            h.num_habitacion, h.num_camas, h.max_personas,
                            t.name AS tipo_habitacion,
                            mp.nombre AS metodo_pago
                    FROM reservas r
                    JOIN habitaciones  h  ON r.id_habitacion  = h.id
                    JOIN type_rooms    t  ON h.id_categoria   = t.id
                    JOIN Metodos_pagos mp ON r.id_metodo_pago = mp.id
                    WHERE r.id = ? AND r.id_user = ?";

            $stmt = $db->prepare($sql);
            if (!$stmt) die("Error: " . $db->error);
            $stmt->bind_param("ii", $id_reserva, $user_id);
            $stmt->execute();
            $reserva = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $conexion->desconectar();
            return $reserva;
        }
    }
?>
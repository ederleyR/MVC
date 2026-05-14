<?php
class EmailModel {
    public function getDatosParaEmail($id_reserva, $user_id) {
        $conexion = new Conexion();
        $db       = $conexion->conectar();

        $sql = "SELECT
                    r.fecha_inicio,
                    r.fecha_final,
                    r.num_personas,
                    r.Descripcion,
                    r.precio,
                    h.num_habitacion,
                    t.name      AS tipo_habitacion,
                    mp.nombre   AS metodo_pago
                FROM reservas r
                JOIN habitaciones  h  ON r.id_habitacion  = h.id
                JOIN type_rooms    t  ON h.id_categoria   = t.id
                JOIN Metodos_pagos mp ON r.id_metodo_pago = mp.id
                WHERE r.id = ? AND r.id_user = ?";

        $stmt = $db->prepare($sql);
        $stmt->bind_param('ii', $id_reserva, $user_id);
        $stmt->execute();
        $datos = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
        return $datos;
    }
}
?>

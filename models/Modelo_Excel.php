<?php 
    class EXCEL{
       public function getReservasExcel($user_id){
            $reservaModel = new Reserva();
            $reservas     = $reservaModel->getReservasByUser($user_id);
            $userModel    = new User();
            $usuario      = $userModel->getUserById($user_id);
            return [
                'usuario'  => $usuario,
                'reservas' => $reservas
            ];
       }
    }
?>
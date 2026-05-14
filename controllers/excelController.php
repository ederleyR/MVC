<?php
    class ExcelController {
        public function descargarExcel() {
        
            if (!isset($_SESSION['user_id'])) {
                header('Location: index.php?action=getFormLoginUser');
                exit;
            }

            $user_id = $_SESSION['user_id'];
            $excelModel = new EXCEL();
            
            // Llamamos una sola vez y obtenemos el paquete de datos
            $datosReporte = $excelModel->getReservasExcel($user_id); 

            // Separamos los datos para que estén listos para 'report/excel.php'
            $usuario  = $datosReporte['usuario'];
            $reservas = $datosReporte['reservas']; 

            // Validamos que existan reservas antes de cargar la vista
            if (empty($reservas)) {
                $_SESSION['errores'] = ['general' => 'No tienes reservas para exportar.'];
                header('Location: index.php?action=getMisReservas');
                exit;
            }

            require_once 'report/excel.php';
        }
    }
?>
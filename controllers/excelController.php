<?php
    class ExcelController {
        public function descargarExcel() {
        
            if (!isset($_SESSION['user_id'])) {
                header('Location: index.php?action=getFormLoginUser');
                exit;
            }

            $user_id = $_SESSION['user_id'];
            $excelModel = new EXCEL();
            
            
            $datosReporte = $excelModel->getReservasExcel($user_id); 

            $usuario  = $datosReporte['usuario'];
            $reservas = $datosReporte['reservas']; 

            if (empty($reservas)) {
                $_SESSION['errores'] = ['general' => 'No tienes reservas para exportar.'];
                header('Location: index.php?action=getMisReservas');
                exit;
            }

            require_once 'report/excel.php';
        }
    }
?>

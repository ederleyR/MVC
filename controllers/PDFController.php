<?php
    class pdfController{
        public function getPDFReserva() {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . SITE_URL . 'index.php?action=getFormLoginUser');
                exit;
            }
            
            $id_reserva   = intval($_POST['id'] ?? 0);
            $PDFModel = new PDF();
            $reserva = $PDFModel->getReservaPDF($id_reserva, $_SESSION['user_id']);
            require_once 'report/PDF.php';
            exit;
        }
    }
?>
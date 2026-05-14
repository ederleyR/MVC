<?php
    session_start();
    require_once 'config/config.php';
    require_once 'models/conexion.php';
    require_once 'models/user.php';
    require_once 'models/reserva.php';
    require_once 'models/Modelo_PDF.php';
    require_once 'models/Modelo_Excel.php';
    require_once 'models/EmailModel.php'; 
    require_once 'controllers/controllerBase.php';
    require_once 'controllers/controllerDashboard.php';
    require_once 'controllers/excelController.php';
    require_once 'controllers/PDFController.php';
    require_once 'controllers/emailController.php';

    $controllerBase = new ControllerBase();
    $controllerDashboard = new ControllerDashboard();
    $excelController     = new ExcelController();
    $PDFController = new pdfController();
    $emailController     = new EmailController();

    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action == 'getFormRegisterUser') {
        $controllerBase->mostrarFormularioRegistrarse();

    } elseif ($action == 'registerUser') {
        $controllerBase->registerUser();

    } elseif ($action == 'getFormLoginUser') {
        $controllerBase->verPaginaInicio('views/auth/login.php');

    } elseif ($action == 'loginUser') {
        $controllerBase->iniciarSesion($_POST);

    } elseif ($action == 'cerrarSesion') {
        $controllerBase->cerrarSesion();

    } elseif ($action == 'getFormInicio') {
        $controllerBase->verPaginaInicio('views/dashboard/home.php');

    // Reservas
    } elseif ($action == 'getFormReserveUser') {
        $controllerDashboard->mostrarFormularioReserva();

    } elseif ($action == 'GuardandoReserva') {
        $controllerDashboard->guardandoReserva();

    } elseif ($action == 'getMisReservas') {
        $controllerDashboard->mostrarMisReservas();

    } elseif ($action == 'getFormEditarReserva') {
        $controllerDashboard->mostrarFormularioEditar();

    } elseif ($action == 'actualizarReserva') {
        $controllerDashboard->actualizarReserva();

    } elseif ($action == 'eliminarReserva') {
        $controllerDashboard->eliminarReserva();

    } elseif ($action == 'AJAXCategoria') {
        $controllerDashboard->AJAXCategoria();

    } elseif ($action == 'getPDFReserva') {
        $PDFController->getPDFReserva();

    } elseif ($action == 'descargarExcel') {
        $excelController->descargarExcel();

    } 
    elseif ($action == 'enviarEmail') {
        $emailController->enviarEmail();
    }
    else {
        $controllerBase->verPaginaInicio('views/dashboard/home.php');
    }
    
?>

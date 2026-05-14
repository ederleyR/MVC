<?php

    class ControllerBase{
        public function verPaginaInicio($pagina){
            include_once $pagina;
        }
        public function validateData($datos){
            $user = new User();
            $existencia = $user->validateEmail($datos);
            $docExiste  = $user->validateDocument($datos); 
            $errores = [];
            if(!isset($datos['document_type_id']) || $datos['document_type_id']=== ''){
                $errores['document_type_id'] = 'El tipo de documento es requerido';
            }
            if (empty(trim($datos['document_number']?? ''))){
                $errores['document_number'] = 'El numero de documento es requerido';
            }elseif ($docExiste == 1) {
                $errores['document_number'] = 'Este número de documento ya está registrado';
            }
            if (empty(trim($datos['name']?? ''))){
                $errores['name'] = 'El nombre es requerido';
            }
            if (empty(trim($datos['last_name']?? ''))){
                $errores['last_name'] = 'El apellido es requerido';
            }elseif(is_numeric($datos['last_name']) || is_numeric($datos['name'])){
                $errores['name'] = 'No se permiten numeros en el nombre o apellido';
            }
            if (empty(trim($datos['phone']?? ''))){
                $errores['phone'] = 'El telefono es requerido';
            }elseif(!is_numeric(trim($datos['phone']))){
                $errores['phone'] = 'El telefono solo admite numeros';
            }
            if (empty(trim($datos['email']?? ''))){
                $errores['email'] = 'El email es requerido';
            }elseif(!filter_var($datos['email'],FILTER_VALIDATE_EMAIL)){
                $errores['email'] = 'el email no es valido';
            }elseif($existencia == 1 ){
                $errores['email']= 'este correo ya esta registrado';
            }
            if(empty($datos['password']?? '')){
                $errores['password'] = 'la contraseña es requerida';
            }elseif(strlen($datos['password']) <8){
                $errores['password'] = 'La contraseña debe tener almenos 8 caracteres';
            }elseif(!preg_match('/[a-z]/', $datos['password']) || 
            !preg_match('/[A-Z]/', $datos['password']) || 
            !preg_match('/[\W_]/', $datos['password'])){
                $errores['password'] = 'La contraseña debe tener  mayúscula, minúscula y un caracter especial.';
            }elseif($datos['password'] != $datos['Cpassword']){
                $errores['password'] = 'las contraseñas no coinciden';
            }
            return $errores;
        }
       public function registerUser(){
            $datos = $_POST;
            
            // Limpiamos mensajes anteriores
            unset($_SESSION['old'], $_SESSION['success'], $_SESSION['errores']);
            
            // 1. Validar campos vacíos o formato
            $errores = $this->validateData($datos);
            if (count($errores) > 0){
                $_SESSION['errores'] = $errores;
                $_SESSION['old'] = $datos;
                header('location: ' . SITE_URL . 'index.php?action=getFormRegisterUser');
                exit;
            }

            $user = new User();
            
            // 2. Validar si el email ya existe en la DB
            $existe = $user->validateEmail($datos);
            if($existe > 0){
                $_SESSION['errores'] = ['email' => 'El correo electrónico ya está en uso.'];
                $_SESSION['old'] = $datos;
                header('location: ' . SITE_URL . 'index.php?action=getFormRegisterUser');
                exit;
            }

            // 3. Encriptar contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $datos['password'] = $passwordHash;

            // 4. Intentar registro
            $resultado = $user->registerUser($datos);
            
            if($resultado > 0){
                $_SESSION['success'] = '¡Cuenta creada con éxito! Ya puedes iniciar sesión.';
                // Tip: Si ya se registró, mándalo al LOGIN, no al formulario de registro otra vez
                header('location: ' . SITE_URL . 'index.php?action=getFormLoginUser');
                exit;
            } else {
                $_SESSION['errores'] = ['general' => 'Hubo un problema con la base de datos.'];
                $_SESSION['old'] = $datos;
                header('location: ' . SITE_URL . 'index.php?action=getFormRegisterUser');
                exit;
            }
        }
        public function iniciarSesion($datos) {
            $user = new User();
            // 1. Cambia tu modelo para que devuelva el objeto/array del usuario, no solo un número
            $usuario = $user->getUserByEmail($datos['email']); 

            if (!$usuario || !password_verify($datos['password'], $usuario['password'])) {
                $errores['login'] = 'Correo o contraseña incorrectos';
                require_once 'views/auth/login.php'; 
            } else {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // --- AQUÍ ESTÁ LA CLAVE ---
                // Guardamos el ID que la base de datos necesita para las reservas
                $_SESSION['user_id'] = $usuario['ID']; 
                $_SESSION['user_email'] = $usuario['email'];
                $_SESSION['user_name'] = $usuario['name']; // Opcional, para saludar en el dashboard
                $_SESSION['logged_in'] = true;
                
                header('location: ' . SITE_URL . 'index.php?action=getFormInicio');
                exit(); 
            }
        }
        public function cerrarSesion() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION = array();
            session_destroy();
            header('location: ' . SITE_URL . 'index.php?action=getFormInicio');
            exit();
        }
        public function DocumentTypes(){
            $conexion = new Conexion();
            $conexion->conectar();
            $sql = "SELECT * FROM document_types";  
            $conexion->query($sql);
            $result = $conexion->getResult();            
            $_SESSION['documentTypes'] = $result->fetch_all(MYSQLI_ASSOC);
            $documentTypes = $_SESSION['documentTypes']; 
            $conexion->desconectar();
            return $documentTypes;
        }
        public function mostrarFormularioRegistrarse() {
            $documentos = new User();
            $documentForm = $documentos->DocumentTypes(); 

            // Cargamos la vista de la reserva
            require_once 'views/auth/register.php';
        }
        
        
    }
?>
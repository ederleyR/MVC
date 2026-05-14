<?php
class User {
    public function validateUser($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $email = $data['email'];
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $conexion->query($sql);  
        $result = $conexion->getResult();
        
        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            $password_db = $usuario['password']; 
            if (password_verify($data['password'], $password_db)) {
                $conexion->desconectar();
                return 1;
            }
        }
        $conexion->desconectar();
        return 0; 
    }
    public function validateEmail($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT * FROM users WHERE email = '$data[email]'";
        $conexion->query($sql);  
        $result = $conexion->getResult();
        $conexion->desconectar();
        if($result->num_rows > 0){
            return 1;
        }
        return 0;
    }
    public function validateDocument($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "SELECT * FROM users WHERE document_number = '$data[document_number]'";
        $conexion->query($sql);  
        $result = $conexion->getResult();
        $conexion->desconectar();
        if($result->num_rows > 0){
            return 1;
        }
        return 0;
    }
    public function registerUser($data) {
        $conexion = new Conexion();
        $conexion->conectar();
        $sql = "INSERT INTO users(document_type_id, document_number, name, last_name, phone, email, password)
        VALUES ('$data[document_type_id]', '$data[document_number]', '$data[name]', '$data[last_name]', '$data[phone]', '$data[email]', '$data[password]')";
        $conexion->query($sql);
        return $conexion->getFilasAfectadas();
        return $afectadas;
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
    public function getUserByEmail($email) {
        $conexion = new Conexion();
        $db = $conexion->conectar(); // Obtenemos la conexión real

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);

        if (!$stmt) {
            die("Error en el prepare del modelo User: " . $db->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Obtenemos el resultado de la consulta
        $result = $stmt->get_result();
        
        // Retornamos los datos como un array asociativo
        $usuario = $result->fetch_assoc();

        $conexion->desconectar();
        return $usuario; 
    }
    public function getUserById($id) {
        $conexion = new Conexion();
        $db = $conexion->conectar();

        $sql  = "SELECT id, name, last_name, email, phone FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conexion->desconectar();
        return $usuario;
    }
    
}
    

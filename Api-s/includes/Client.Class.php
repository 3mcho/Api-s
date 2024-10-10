<?php
    require_once('Database.class.php');


    class Client{
        public static function insert_client($nombre_completo,$direccion, $localidad,$cp,$telefono,$correo_electronico, $estatus){
            $database = new Database();
            $conn = $database -> getConnection();

            $stmt = $conn -> prepare('INSERT INTO cliente(nombre_completo,direccion,localidad,cp,telefono,correo_electronico,estatus)
                VALUES(:nombre_completo,:direccion,:localidad,:cp,:telefono,:correo_electronico,:estatus)');
            $stmt->bindParam(':nombre_completo',$nombre_completo);
            $stmt->bindParam(':direccion',$direccion);
            $stmt->bindParam(':localidad',$localidad);
            $stmt->bindParam(':cp',$cp);
            $stmt->bindParam(':telefono',$telefono);
            $nuevoDato = intval($estatus);
            $stmt->bindParam(':correo_electronico',$correo_electronico);
            $stmt->bindParam(':estatus',$nuevoDato);

            if($stmt->execute()){
                header('HTTP/1.1 201 CLIENTE CREADO');
            }else{
                header('HTTP/1.1 204 CLIENTE NO CREADO');
            }
        }


        /* VERIFICA SI SE ENCUENTRA EL USUARIO*/
        public static function usuario_existe($correo_electronico, $contraseña) {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta
            $stmt = $conn->prepare('SELECT * FROM usuarios WHERE correo_electronico =:correo_electronico AND contraseña =:password;');
            $stmt->bindParam(':correo_electronico',$correo_electronico);
            $stmt->bindParam(':password',$contraseña);
            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si el cliente existe
            if ($stmt->rowCount() > 0) {
                header('HTTP/1.1 201 ENCONTRADO');
                return true; // Cliente encontrado
                
            } else {
                header('HTTP/1.1 201 NO ENCONTRADO');
                return false; // Cliente no encontrado
            }
        }
    }
?>
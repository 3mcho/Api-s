<?php
    require_once('Database.class.php');


    class Client{
       
        public static function usuario_existe($correo_electronico) {
            $database = new Database();
            $conn = $database->getConnection();
        
            // Preparar la consulta, seleccionando solo la clave foránea (fk_cliente) y otros campos
            $stmt = $conn->prepare('SELECT nombre_usuario, alias, password, fk_cliente FROM usuariosapp WHERE correo_electronico = :correo_electronico');
            $stmt->bindParam(':correo_electronico', $correo_electronico);
            
            // Ejecutar la consulta
            $stmt->execute();
        
            // Verificar si el cliente existe
            if ($stmt->rowCount() > 0) {
                // Obtener el resultado
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nombre_usuario = $row['nombre_usuario'];
                $alias = $row['alias'];
                $password = $row['password'];
                $fk_cliente = $row['fk_cliente'];
        
                // Devolver la clave foránea y otros datos en la respuesta JSON
                echo json_encode([
                    'nombre_usuario' => $nombre_usuario,
                    'alias' => $alias,
                    'password' => $password,
                    'fk_cliente' => $fk_cliente
                ]);
                return true; 
            } 
            else 
            {
                echo json_encode([
                    'status' => 'Incompleto',
                    'message' => 'No se encuentra el usuario'
                ]);
                return false; // Cliente no encontrado
            }
        }
        

        /*
        ------------------FUNCION PARA CREAR LOS USUARIOS------------------------------------
        */
        public static function crear_usuario($nombre_usuario, $correo_electronico, $password, $tipo_plan, $activo){

            $database = new Database();
            $conn = $database -> getConnection();
            

            //CONSULTA PARA AGREGAR USUARIO
            $stmt = $conn -> prepare('INSERT INTO usuarios(nombre_usuario,correo_electronico, contraseña, tipo_plan, activo, fk_cliente) 
            VALUES(:nombre_usuario, :correo_electronico, :password, :tipo_plan, :activo, :fk_cliente )');

            $stmt -> bindParam(':nombre_usuario', $nombre_usuario);
            $stmt -> bindParam(':correo_electronico', $correo_electronico);
            $stmt -> bindParam(':password', $password);
            $stmt -> bindParam(':tipo_plan', $tipo_plan);
            
                if($stmt -> execute()){
                
                    //Mensaje en forma de json
                    http_response_code(201);
                    echo json_encode([
                    'status' => 'completado',
                    'message' => 'Usuario creado exitosamente'// Devuelve el ID del nuevo usuario
                    ]);
                }else{
                    http_response_code(400);
                    echo json_encode([
                    'status' => 'error',
                    'message' => 'Usuario no creado exitosamente'// Devuelve el ID del nuevo usuario
                    ]);
                }
                  
        }

        /*
        ------------------FUNCION PARA OBTENER EL LOS CONTRATOS LIGADOS AL CLIENTE POR MEDIO DEL ID-----------------------------------
        */
        // Función para consultar los contratos de un cliente por su ID
        public static function consultar_contratos_por_id($id_cliente) {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar los contratos por ID del cliente
            $stmt_contratos = $conn->prepare('SELECT * FROM contratos WHERE id_cliente = :id_cliente');
            $stmt_contratos->bindParam(':id_cliente', $id_cliente);
            $stmt_contratos->execute();

            $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);

            if ($contratos) {
                return $contratos; // Devolver los contratos del cliente
            } else {
                return false; // No se encontraron contratos para este cliente
            }
        }
        

        /* 
        ------------------FUNCION PARA OBTENER LOS DATOS DEL CLIENTE POR MEDIO DEL ID-----------------------------------
        */
        public static function get_Client_by_id($id_cliente){
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el Cliente por id
            $stmt = $conn->prepare('SELECT * FROM Clientes WHERE id_cliente = :id_cliente');
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->execute();

            // Obtener el Usuarios
            $Client = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($Client) {
                return $Client;
            } else {
                return null; // Cliente no encontrado
            }
        }
    }
?>

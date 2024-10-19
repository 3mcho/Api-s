<?php
    require_once('Database.class.php');


    class Client{
       
        public static function usuario_existe($correo_electronico) {
            $database = new Database();
            $conn = $database->getConnection();
        
            // Preparar la consulta, seleccionando solo la clave foránea (fk_cliente) y otros campos
            $stmt = $conn->prepare('SELECT nombre_usuario, alias, password, fk_cliente FROM UsuariosApp WHERE correo_electronico = :correo_electronico');
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
                    'message' => 'Error',
                    'status' => 'A102'
                ]);
                return false; // Cliente no encontrado
            }
        }
        

        /*
        ------------------FUNCION PARA CREAR LOS USUARIOS------------------------------------
        */
        public static function crear_usuario($nombre_usuario, $alias, $correo_electronico, $password, $activo, $fk_cliente){

            $database = new Database();
            $conn = $database -> getConnection();
            

            //CONSULTA PARA AGREGAR USUARIO
            $stmt = $conn -> prepare('INSERT INTO UsuariosApp(nombre_usuario,alias ,correo_electronico, password, activo, fk_cliente) 
            VALUES(:nombre_usuario,:alias , :correo_electronico, :password, :activo, :fk_cliente )');

            $stmt -> bindParam(':nombre_usuario', $nombre_usuario);
            $stmt -> bindParam(':alias', $alias);
            $stmt -> bindParam(':correo_electronico', $correo_electronico);
            $stmt -> bindParam(':password', $password);
            $stmt -> bindParam(':activo', $activo);
            $stmt -> bindParam(':fk_cliente', $fk_cliente);
            
                if($stmt -> execute()){
                    //Mensaje en forma de json
                    http_response_code(201);
                    header('Content-Type: application/json');
                    echo json_encode([
                    'message' => 'CORRECTO:',
                    'status' => 'B4C5'
                    ]);
                }else{
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode([
                    'message' => 'ERROR:',
                    'status' => 'D204'
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
            $stmt_contratos = $conn->prepare('SELECT * FROM contratos WHERE fk_cliente = :id_cliente');
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

        //Validacion de cliente en la tabla UsuariosApp
        public static function client_validation_in_usuarios_app($id_cliente, $email)
        {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el Cliente por el ID o el EMAIL
            $stmt = $conn->prepare('SELECT * FROM UsuariosApp WHERE fk_cliente = :id_cliente or correo_electronico = :email');
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result)
            {
                return "E001";
            }
            else 
            {
                return "A102";
            }
        }

        //Validacion de cliente en la tabla Clientes
        public static function client_validation_in_clientes($id_cliente, $email)
        {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el Cliente por el ID o el EMAIL
            $stmt = $conn->prepare('SELECT * FROM Clientes WHERE id_cliente = :id_cliente or correo_electronico = :email');
            $stmt->bindParam(':id_cliente', $id_cliente);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result)
            {
                return "E001";
            }
            else 
            {
                return "A102";
            }
        }
    }
?>

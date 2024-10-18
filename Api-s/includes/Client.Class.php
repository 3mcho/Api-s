<?php
    require_once('Database.class.php');


    class Client{
       
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
                echo json_encode([
                    'status' => 'Completado',
                    'message' => 'El cliente si existe '// Devuelve el ID del nuevo usuario
                    ]);
                return true; // Cliente encontrado
                
            } else {
                echo json_encode([
                    'status' => 'Incompleto',
                    'message' => 'No se encuentra el usuario '// Devuelve el ID del nuevo usuario
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
        ------------------FUNCION PARA OBTENER EL LOS CONTRATOS LIGADOS AL CLIENTE POR MEDIO DEL CORREO-----------------------------------
        */
        public static function consultar_contratos_por_correo($correo_electronico) {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el cliente por correo electrónico
            $stmt = $conn->prepare('SELECT id_cliente FROM clientes WHERE correo_electronico = :correo_electronico');
            $stmt->bindParam(':correo_electronico', $correo_electronico);
            $stmt->execute();

            // Obtener el cliente
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cliente) {
                // Si el cliente existe, buscar sus contratos
                $stmt_contratos = $conn->prepare('SELECT * FROM contratos WHERE id_cliente = :id_cliente');
                $stmt_contratos->bindParam(':id_cliente', $cliente['id_cliente']);
                $stmt_contratos->execute();

                $contratos = $stmt_contratos->fetchAll(PDO::FETCH_ASSOC);

                if ($contratos) {
                    return $contratos; // Devolver los contratos del cliente
                } else {
                    return false; // No se encontraron contratos para este cliente
                }
            } else {
                return false; // Cliente no encontrado
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

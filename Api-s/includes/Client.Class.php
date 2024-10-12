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
            

            //Se obtiene el id_cliente mediante el correo electronico
            $fk_cliente= Client::obtener_fk_cliente($correo_electronico);
            $stmt -> bindParam(':fk_cliente', $fk_cliente);


            //Se llama al metodo el cual devuelve el estatus del cliente
            $estatus = Client::comprueba_estatus_cliente($fk_cliente);
            $stmt -> bindParam(':activo', $estatus);

            if( $estatus == 1){

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
            }else{
                http_response_code(201);
                    echo json_encode([
                    'status' => 'No existe',
                    'message' => 'El cliente aun no esta dado de alta '// Devuelve el ID del nuevo usuario
                    ]);

            }
                  
        }


        /*
        ------------------FUNCION PARA COMPROBAR SI EL USUARIO ESTA DADO DE ALTA MEDIANTE EL ESTATUS------------------------------------
        */
        public static function comprueba_estatus_cliente($fk_cliente){

            $database = new Database();
            $conn = $database -> getConnection();
            $stmt = $conn -> prepare('SELECT estatus FROM clientes WHERE id_cliente = :fk_cliente');

            
            $stmt->bindParam(':fk_cliente', $fk_cliente, PDO::PARAM_INT);

            $stmt->execute();

            //VUELVE LA CONSULTA A UN ARRAY
            $estatus = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($estatus) {
                //EN CASO DE SER VERDADERO 
                return $estatus['estatus']; 
                // Retorna el valor de la columna 'estatus'
            } else {
                //EN CASO DE SER FALSO
                return null; // Retorna null si no se encuentra el cliente
            }


        }

        /*
        ------------------FUNCION PARA OBTENER EL ID DEL CLIENTE MEDIANTE EL CORREO ELECTRONICO REGISTRADO-----------------------------------
        */
        public static function obtener_fk_cliente($correo_electronico){
            $database = new Database();
            $conn = $database -> getConnection();
            $stmt = $conn -> prepare('SELECT id_cliente FROM clientes WHERE correo_electronico = :correo_electronico' );

            $stmt -> bindParam(':correo_electronico', $correo_electronico, PDO::PARAM_INT);
            $stmt -> execute();

            $fk_cliente = $stmt -> fetch(PDO::FETCH_ASSOC);

            if($fk_cliente !== 0 ){
                return $fk_cliente['id_cliente'];
            }else{
                return null;
            }

        }

        /*
        ------------------FUNCION PARA OBTENER EL LOS CONTRATOS LIGADOS AL CLIENTE POR MEDIO DEL CORREO-----------------------------------
        */
                // Función para consultar los contratos de un cliente por su correo electrónico
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
    }
?>
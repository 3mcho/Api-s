<?php
    require_once('Database.class.php');


    class Client{
       /*
        ------------------FUNCION PARA VERIFICAR SI EL USUARIO EXISTE----------------------------------
        */
        public static function usuario_existe($correo_electronico) {
            $database = new Database();
            $conn = $database->getConnection();
        
            // Preparar la consulta
            $stmt = $conn->prepare('SELECT nombre_usuario, alias, password, fk_cliente FROM usuarios_app WHERE correo_electronico = :correo_electronico');
            $stmt->bindParam(':correo_electronico', $correo_electronico);
        
            // Ejecutar la consulta
            $stmt->execute();
        
            // Verificar si el cliente existe
            if ($stmt->rowCount() > 0) {
                // Obtener el resultado
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'nombre_usuario' => $row['nombre_usuario'],
                    'alias' => $row['alias'],
                    'password' => $row['password'],
                    'fk_cliente' => $row['fk_cliente']
                ]; 
            } 
            else 
            {
                return false; // Cliente no encontrado
            }
        }
        
        /*
        ------------------FUNCION PARA OBTENER EL LAS DIRECCIONES MEDIANTE EL ID DEL CLIENTE----------------------------------
        */
        public static function usuario_direcciones($id_cliente) {
            $database = new Database();
            $conn = $database->getConnection();
        
            // Consulta para obtener todas las direcciones asociadas al cliente
            $query = "SELECT * FROM direcciones WHERE fk_cliente = :clientId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':clientId', $id_cliente, PDO::PARAM_INT);
            $stmt->execute();
        
            // Obtener todas las direcciones asociadas
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Verificar si se encontraron direcciones
            if ($addresses) {
                // Eliminar el campo 'fk_cliente' de cada dirección
                foreach ($addresses as &$address) {
                    unset($address['fk_cliente']);
                }
                return $addresses;
            } else {
                return null; // Si no hay direcciones para este cliente
            }
        }

        /*
        ------------------FUNCION PARA CREAR LOS USUARIOS------------------------------------
        */
        public static function crear_usuario($nombre_usuario, $alias, $correo_electronico, $password, $activo, $fk_cliente){

            $database = new Database();
            $conn = $database -> getConnection();
            

            //CONSULTA PARA AGREGAR USUARIO
            $stmt = $conn -> prepare('INSERT INTO usuarios_app(nombre_usuario,alias ,correo_electronico, password, activo, fk_cliente) 
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
        public static function consultar_contratos_por_id($id_cliente) {
            $database = new Database();
            $conn = $database->getConnection();
        
            try {
                // Consulta adaptada a la nueva base de datos
                $sql = "
                    SELECT 
                        c.id_contrato,
                        c.fecha_inicio_contrato,
                        c.fecha_fin_contrato,
                        c.total_meses_contrato,
                        c.estado,
                        c.monto_total_contrato,
                        c.monto_total_mensualidad,
                        p.id_precontrato,
                        np.nombre_paquete,
                        np.precio,
                        np.caracteristicas_paquete,
                        np.velocidad_paquete
                    FROM contratos c
                    INNER JOIN precontratos p ON c.fk_precontrato = p.id_precontrato
                    INNER JOIN nombres_paquetes np ON p.fk_paquete = np.id_nombre_paquete
                    WHERE p.fk_cliente = :id_cliente;
                ";
        
                // Preparar y ejecutar la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);
                $stmt->execute();
        
                // Recuperar los resultados
                $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
                // Retornar los resultados o false si no hay contratos
                return $contratos ?: false;
        
            } catch (PDOException $e) {
                // Manejo de errores en caso de problemas con la consulta
                throw new Exception("Error al consultar los contratos: " . $e->getMessage());
            }
        }
        

        /* 
        ------------------FUNCION PARA OBTENER LOS DATOS DEL CLIENTE POR MEDIO DEL ID-----------------------------------
        */
        public static function get_Client_by_id($id_cliente){
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el Cliente por id
            $stmt = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente');
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
            $stmt = $conn->prepare('SELECT * FROM usuarios_app WHERE fk_cliente = :id_cliente or correo_electronico = :email');
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

        /* 
        ------------------FUNCION PARA VALIDAR EL CLIENTE-----------------------------------
        */
        public static function client_validation_in_clientes($id_cliente, $email)
        {
            $database = new Database();
            $conn = $database->getConnection();

            // Preparar la consulta para buscar el Cliente por el ID o el EMAIL
            $stmt = $conn->prepare('SELECT * FROM clientes WHERE id_cliente = :id_cliente or correo_electronico = :email');
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

         /* 
        ------------------FUNCION PARA OBTENER LOS DATOS DEL CLIENTE POR MEDIO DEL CORREO-----------------------------------
        */
        public static function get_client_by_email($email) {
            $database = new Database();
            $conn = $database->getConnection();
    
            $stmt = $conn->prepare('SELECT * FROM clientes WHERE correo_electronico = :email');
            $stmt->bindParam(':email', $email);
    
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    header('Content-Type: application/json');
                    header('HTTP/1.1 200 OK');
                    echo json_encode($result);
                } else {
                    header('HTTP/1.1 404 Cliente no encontrado');
                    echo json_encode(['message' => 'Cliente no encontrado']);
                }
            } else {
                header('HTTP/1.1 500 Error en la consulta');
                echo json_encode(['message' => 'Error en la consulta']);
            }
        }

         /* 
        ------------------FUNCION PARA ACTUALIZAR ALIAS Y PASSWORD (AUTOR: MOISES)-----------------------------------
        */

        public static function updateUsuariosApp($email, $newAlias, $newPassword){
            $database = new Database();
            $conn = $database -> getConnection();
            $stmt = $conn->prepare('UPDATE usuarios_app SET alias = :newAlias, password= :newPassword WHERE correo_electronico = :email');
            $stmt->bindParam(':newAlias', $newAlias);
            $stmt->bindParam(':newPassword', $newPassword);
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

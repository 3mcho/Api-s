<?php 

// Clase para la conexión a la base de datos
class Database {
    private $host = 'fdb1029.awardspace.net'; // Solo el host
    private $user = '4535717_conectat247bd';  // Usuario de la base de datos
    private $password = 'Qwerty247+';  // Contraseña de la base de datos
    private $database = '4535717_conectat247bd';  // Nombre de la base de datos

    public function getConnection() {
        $hostDB = "mysql:host=".$this->host.";dbname=".$this->database.";port=3306;";  // Añadir puerto 3306

        try {
            $connection = new PDO($hostDB, $this->user, $this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            die("ERROR: " . $e->getMessage());
        }
    }
}
?>
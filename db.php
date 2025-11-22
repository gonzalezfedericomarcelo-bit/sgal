<?php
// sgal/config/db.php

class Database {
    // Parámetros de conexión de XAMPP
    private $host = 'localhost';
    private $db_name = 'sgal';
    private $username = 'root';
    private $password = '';     // Asumimos que no tienes contraseña en XAMPP
    private $conn;

    /**
     * Obtiene la conexión a la base de datos.
     * @return PDO|null
     */
    public function connect() {
        $this->conn = null;
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8';

        try {
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            // Si la conexión falla (ej. contraseña de XAMPP incorrecta), MUESTRA el error.
            echo 'Error de Conexión: ' . $e->getMessage();
            exit; // Detiene el script si no se puede conectar
        }
        return $this->conn;
    }
}
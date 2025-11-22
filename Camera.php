<?php
// sgal/core/Camera.php

require_once __DIR__ . '/../config/db.php';

class Camera {
    private $conn;
    private $table = 'camaras';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Crea una nueva cámara en la base de datos.
     * @param string $nombre
     * @param string $ubicacion
     * @param string $url_stream
     * @return bool
     */
    public function createCamera($nombre, $ubicacion, $url_stream) {
        $query = "INSERT INTO " . $this->table . " (nombre_camara, ubicacion, url_stream_ip) VALUES (:nombre, :ubicacion, :url)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':url', $url_stream);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createCamera: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene todas las cámaras ordenadas por nombre.
     * @return array
     */
    public function getAllCameras() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nombre_camara ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllCameras: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los datos de una cámara específica por su ID.
     * @param int $id
     * @return array|false
     */
    public function getCameraById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getCameraById: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza los datos de una cámara.
     * @param int $id
     * @param string $nombre
     * @param string $ubicacion
     * @param string $url_stream
     * @return bool
     */
    public function updateCamera($id, $nombre, $ubicacion, $url_stream) {
        $query = "UPDATE " . $this->table . " SET nombre_camara = :nombre, ubicacion = :ubicacion, url_stream_ip = :url WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            $stmt->bindParam(':url', $url_stream);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateCamera: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina una cámara de la base de datos.
     * @param int $id
     * @return bool
     */
    public function deleteCamera($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteCamera: ' . $e->getMessage());
            return false;
        }
    }
}
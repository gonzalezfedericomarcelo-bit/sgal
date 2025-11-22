<?php
// sgal/core/Access.php

require_once __DIR__ . '/../config/db.php';

class Access {
    private $conn;
    private $table = 'accesos';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function registrarEntrada($tipo, $nombre_placa, $documento, $motivo, $usuario_registro_id, $camara_vinculada_id = null) {
        $query = "INSERT INTO " . $this->table . "
                  (tipo, nombre_placa, documento, motivo, fecha_entrada, usuario_registro_id, camara_vinculada_id)
                  VALUES
                  (:tipo, :nombre_placa, :documento, :motivo, NOW(), :usuario_registro_id, :camara_vinculada_id)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':nombre_placa', $nombre_placa);
            $stmt->bindParam(':documento', $documento);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':usuario_registro_id', $usuario_registro_id, PDO::PARAM_INT);
            $stmt->bindParam(':camara_vinculada_id', $camara_vinculada_id, $camara_vinculada_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en registrarEntrada: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function registrarSalida($acceso_id) {
        $query = "UPDATE " . $this->table . "
                  SET fecha_salida = NOW()
                  WHERE id = :acceso_id AND fecha_salida IS NULL";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':acceso_id', $acceso_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log('Error en registrarSalida: ' . (string)$e->getMessage());
            return false;
        }
    }

    public function getHistorialAccesos() {
        $query = "SELECT 
                    a.id, a.tipo, a.nombre_placa, a.documento, a.motivo, a.fecha_entrada, a.fecha_salida,
                    u.nombre_completo AS usuario_registro,
                    c.nombre_camara AS camara_vinculada
                  FROM " . $this->table . " a
                  LEFT JOIN usuarios u ON a.usuario_registro_id = u.id
                  LEFT JOIN camaras c ON a.camara_vinculada_id = c.id
                  ORDER BY a.fecha_entrada DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getHistorialAccesos: ' . (string)$e->getMessage());
            return [];
        }
    }

    public function getAccesosActivos() {
        $query = "SELECT id, tipo, nombre_placa, documento, fecha_entrada
                  FROM " . $this->table . "
                  WHERE fecha_salida IS NULL
                  ORDER BY fecha_entrada ASC"; 
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAccesosActivos: ' . (string)$e->getMessage());
            return [];
        }
    }

    public function getCamarasDisponibles() {
        try {
            $stmt = $this->conn->prepare("SELECT id, nombre_camara, ubicacion FROM camaras ORDER BY nombre_camara");
            $stmt->execute();
            // --- INICIO DE LA CORRECCIÓN ---
            // Se completa la línea que estaba cortada y se añaden los bloques 'catch' y '}' finales.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getCamarasDisponibles: ' . (string)$e->getMessage());
            return [];
        }
    }
} // <-- Corchete de cierre de la CLASE 'Access' que faltaba.
// --- FIN DE LA CORRECCIÓN ---
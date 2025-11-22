<?php
// sgal/core/Document.php

require_once __DIR__ . '/../config/db.php';

class Document {
    private $conn;
    private $uploadDir = __DIR__ . '/../public/uploads/documents/';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();

        if (!file_exists($this->uploadDir)) {
            @mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getVehiculos() {
        $query = "SELECT id, placa, marca, modelo FROM vehiculos ORDER BY placa ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getVehiculos: ' . (string)$e->getMessage());
            return [];
        }
    }

    public function getConductores() {
        $query = "SELECT id, nombre_completo FROM usuarios WHERE activo = 1 ORDER BY nombre_completo ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getConductores: ' . (string)$e->getMessage());
            return [];
        }
    }

    public function createDocument($fileData, $nombre_doc, $entidad_tipo, $entidad_id, $fecha_vencimiento, $usuario_carga_id) {
        if (!isset($fileData) || $fileData['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error en la subida del archivo. Código: ' . $fileData['error']];
        }
        try {
            $fileName = uniqid() . '-' . basename($fileData['name']);
            $targetPath = $this->uploadDir . $fileName;
            if (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
                throw new Exception('No se pudo mover el archivo subido al directorio de destino.');
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al guardar el archivo: ' . $e->getMessage()];
        }
        $query = "INSERT INTO documentos 
                  (nombre_doc, entidad_tipo, entidad_id, fecha_vencimiento, archivo_path, usuario_carga_id, fecha_carga)
                  VALUES
                  (:nombre_doc, :entidad_tipo, :entidad_id, :fecha_vencimiento, :archivo_path, :usuario_carga_id, NOW())";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_doc', $nombre_doc);
            $stmt->bindParam(':entidad_tipo', $entidad_tipo);
            $stmt->bindParam(':entidad_id', $entidad_id, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_vencimiento', $fecha_vencimiento, $fecha_vencimiento ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindParam(':archivo_path', $fileName);
            $stmt->bindParam(':usuario_carga_id', $usuario_carga_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Documento subido y registrado exitosamente.'];
            } else {
                @unlink($targetPath);
                throw new Exception('Error al registrar el documento en la base de datos.');
            }
        } catch (PDOException $e) {
            @unlink($targetPath);
            error_log('Error en createDocument (SQL): ' . (string)$e->getMessage());
            return ['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    public function getAllDocuments() {
        $query = "SELECT 
                    d.id, d.nombre_doc, d.entidad_tipo, d.entidad_id, d.fecha_vencimiento, d.archivo_path, d.fecha_carga,
                    u.nombre_completo AS usuario_carga,
                    CASE 
                        WHEN d.entidad_tipo = 'VEHICULO' THEN v.placa
                        WHEN d.entidad_tipo = 'CONDUCTOR' THEN c.nombre_completo
                        ELSE 'N/A'
                    END AS entidad_nombre
                  FROM documentos d
                  JOIN usuarios u ON d.usuario_carga_id = u.id
                  LEFT JOIN vehiculos v ON d.entidad_tipo = 'VEHICULO' AND d.entidad_id = v.id
                  LEFT JOIN usuarios c ON d.entidad_tipo = 'CONDUCTOR' AND d.entidad_id = c.id
                  ORDER BY d.fecha_carga DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllDocuments: ' . (string)$e->getMessage());
            return [];
        }
    }

    private function getDocumentById($id) {
        $query = "SELECT archivo_path FROM documentos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteDocument($id) {
        $doc = $this->getDocumentById($id);
        $this->conn->beginTransaction();
        try {
            $query = "DELETE FROM documentos WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception('No se pudo borrar el registro de la base de datos.');
            }
            if ($doc && !empty($doc['archivo_path'])) {
                $filePath = $this->uploadDir . $doc['archivo_path'];
                if (file_exists($filePath)) {
                    if (!@unlink($filePath)) {
                        throw new Exception('No se pudo borrar el archivo físico. Revise los permisos.');
                    }
                }
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('Error en deleteDocument: ' . $e->getMessage());
            return false;
        }
    }
    
    public function getExpiringDocuments() {
        $query = "SELECT 
                    d.nombre_doc, d.fecha_vencimiento, d.archivo_path,
                    DATEDIFF(d.fecha_vencimiento, CURDATE()) AS dias_restantes,
                    CASE 
                        WHEN d.entidad_tipo = 'VEHICULO' THEN CONCAT('VEHICULO: ', v.placa)
                        WHEN d.entidad_tipo = 'CONDUCTOR' THEN CONCAT('CONDUCTOR: ', c.nombre_completo)
                        ELSE 'N/A'
                    END AS entidad_nombre
                  FROM documentos d
                  LEFT JOIN vehiculos v ON d.entidad_tipo = 'VEHICULO' AND d.entidad_id = v.id
                  LEFT JOIN usuarios c ON d.entidad_tipo = 'CONDUCTOR' AND d.entidad_id = c.id
                  WHERE 
                    d.fecha_vencimiento IS NOT NULL
                    AND d.fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                  ORDER BY dias_restantes ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getExpiringDocuments: ' . (string)$e->getMessage());
            return [];
        }
    }
}
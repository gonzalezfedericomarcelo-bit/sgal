<?php
// sgal/core/Logistics.php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Config.php';

class Logistics {
    private $conn;
    private $config; 

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
        $this->config = new Config();
    }

    public function createAlmacen($nombre, $ubicacion) {
        $query = "INSERT INTO almacenes (nombre, ubicacion) VALUES (:nombre, :ubicacion)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createAlmacen: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllAlmacenes() {
        $query = "SELECT * FROM almacenes ORDER BY nombre ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllAlmacenes: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getAlmacenById($id) {
        $query = "SELECT * FROM almacenes WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAlmacenById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateAlmacen($id, $nombre, $ubicacion) {
        $query = "UPDATE almacenes SET nombre = :nombre, ubicacion = :ubicacion WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':ubicacion', $ubicacion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateAlmacen: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteAlmacen($id) {
        $query = "DELETE FROM almacenes WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteAlmacen: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function createProducto($sku, $nombre, $descripcion) {
        $query = "INSERT INTO productos (sku, nombre, descripcion) VALUES (:sku, :nombre, :descripcion)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createProducto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllProductos() {
        $query = "SELECT * FROM productos ORDER BY nombre ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllProductos: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getProductoById($id) {
        $query = "SELECT * FROM productos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 1: De $this.conn a $this->conn
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getProductoById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateProducto($id, $sku, $nombre, $descripcion) {
        $query = "UPDATE productos SET sku = :sku, nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 2: De $this.conn a $this->conn
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateProducto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteProducto($id) {
        $query = "DELETE FROM productos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 3: De $this.conn a $this->conn
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteProducto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getInventoryStock($producto_id, $almacen_id) {
        $query = "SELECT cantidad_actual FROM inventario 
                  WHERE producto_id = :producto_id AND almacen_id = :almacen_id";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 4: De $this.conn a $this->conn
            $stmt->bindParam(':producto_id', $producto_id, PDO::PARAM_INT);
            $stmt->bindParam(':almacen_id', $almacen_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? (int)$resultado['cantidad_actual'] : 0;
        } catch (PDOException $e) {
            error_log('Error en getInventoryStock: ' . (string)$e->getMessage());
            return 0;
        }
    }
    public function requestMovement($producto_id, $almacen_origen_id, $almacen_destino_id, $cantidad, $usuario_solicitud_id) {
        if ($almacen_origen_id == $almacen_destino_id) {
            return ['success' => false, 'message' => 'El almacén de origen y destino no pueden ser el mismo.'];
        }
        if ($cantidad <= 0) {
            return ['success' => false, 'message' => 'La cantidad debe ser mayor a cero.'];
        }
        $stock_en_origen = $this->getInventoryStock($producto_id, $almacen_origen_id);
        if ($stock_en_origen < $cantidad) {
            return ['success' => false, 'message' => 'Stock insuficiente en el almacén de origen. Stock actual: ' . $stock_en_origen];
        }
        $doble_aprobacion = $this->config->getSetting('DOBLE_APROBACION_LOGISTICA');
        if ($doble_aprobacion === '1') {
            return $this->createPendingMovement($producto_id, $almacen_origen_id, $almacen_destino_id, $cantidad, $usuario_solicitud_id);
        } else {
            return $this->executeAutoApproval( // CORRECCIÓN 5: De $this.executeAutoApproval a $this->executeAutoApproval
                $producto_id, 
                $almacen_origen_id, 
                $almacen_destino_id, 
                $cantidad, 
                $usuario_solicitud_id
            );
        }
    }
    private function createPendingMovement($producto_id, $almacen_origen_id, $almacen_destino_id, $cantidad, $usuario_solicitud_id) {
        $query = "INSERT INTO movimientos_stock 
                  (producto_id, almacen_origen_id, almacen_destino_id, cantidad, 
                   estado, usuario_solicitud_id, fecha_solicitud)
                  VALUES
                  (:producto_id, :almacen_origen_id, :almacen_destino_id, :cantidad, 
                   'PENDIENTE', :usuario_solicitud_id, NOW())";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 6: De $this.conn a $this->conn
            $params = [
                'producto_id' => $producto_id,
                'almacen_origen_id' => $almacen_origen_id,
                'almacen_destino_id' => $almacen_destino_id,
                'cantidad' => $cantidad,
                'usuario_solicitud_id' => $usuario_solicitud_id
            ];
            if ($stmt->execute($params)) {
                return ['success' => true, 'message' => 'Solicitud de movimiento creada. Queda pendiente de aprobación.'];
            } else {
                return ['success' => false, 'message' => 'Error al crear la solicitud.'];
            }
        } catch (PDOException $e) {
            error_log('Error en createPendingMovement: ' . (string)$e->getMessage());
            return ['success' => false, 'message' => 'Error de BBDD: ' . $e->getMessage()];
        }
    }
    private function executeAutoApproval($producto_id, $almacen_origen_id, $almacen_destino_id, $cantidad, $usuario_solicitud_id) {
        $this->conn->beginTransaction();
        try {
            $query_origen = "UPDATE inventario 
                             SET cantidad_actual = cantidad_actual - :cantidad 
                             WHERE producto_id = :producto_id AND almacen_id = :almacen_id";
            $stmt_origen = $this->conn->prepare($query_origen); // CORRECCIÓN 7: De $this.conn a $this->conn
            $stmt_origen->execute([
                'cantidad' => $cantidad,
                'producto_id' => $producto_id,
                'almacen_id' => $almacen_origen_id
            ]);
            $query_destino = "INSERT INTO inventario (producto_id, almacen_id, cantidad_actual)
                              VALUES (:producto_id, :almacen_id, :cantidad)
                              ON DUPLICATE KEY UPDATE
                              cantidad_actual = cantidad_actual + :cantidad";
            $stmt_destino = $this->conn->prepare($query_destino); // CORRECCIÓN 8: De $this.conn a $this->conn
            $stmt_destino->execute([
                'producto_id' => $producto_id,
                'almacen_id' => $almacen_destino_id,
                'cantidad' => $cantidad
            ]);
            $query_mov = "INSERT INTO movimientos_stock 
                          (producto_id, almacen_origen_id, almacen_destino_id, cantidad, estado, 
                           usuario_solicitud_id, usuario_aprobacion_id, 
                           fecha_solicitud, fecha_aprobacion)
                          VALUES
                          (:producto_id, :almacen_origen_id, :almacen_destino_id, :cantidad, 'APROBADO', 
                           :usuario_solicitud_id, :usuario_aprobacion_id, 
                           NOW(), NOW())";
            $stmt_mov = $this->conn->prepare($query_mov); // CORRECCIÓN 9: De $this.conn a $this->conn
            $stmt_mov->execute([
                'producto_id' => $producto_id,
                'almacen_origen_id' => $almacen_origen_id,
                'almacen_destino_id' => $almacen_destino_id,
                'cantidad' => $cantidad,
                'usuario_solicitud_id' => $usuario_solicitud_id,
                'usuario_aprobacion_id' => $usuario_solicitud_id
            ]);
            $this->conn->commit();
            return ['success' => true, 'message' => 'Movimiento aprobado y ejecutado automáticamente. El stock ha sido actualizado.'];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Error durante la transacción de auto-aprobación: ' . $e->getMessage()];
        }
    }
    public function getPendingMovements() {
        $query = "SELECT 
                    ms.id, ms.cantidad, ms.fecha_solicitud,
                    p.nombre AS producto_nombre,
                    ao.nombre AS almacen_origen,
                    ad.nombre AS almacen_destino,
                    u.nombre_completo AS usuario_solicitud
                  FROM movimientos_stock ms
                  JOIN productos p ON ms.producto_id = p.id
                  JOIN almacenes ao ON ms.almacen_origen_id = ao.id
                  JOIN almacenes ad ON ms.almacen_destino_id = ad.id
                  JOIN usuarios u ON ms.usuario_solicitud_id = u.id
                  WHERE ms.estado = 'PENDIENTE'
                  ORDER BY ms.fecha_solicitud ASC";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 10: De $this.conn a $this->conn (Línea 263 en tu error)
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getPendingMovements: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function approveMovement($movimiento_id, $aprobador_id) {
        $query_get = "SELECT * FROM movimientos_stock WHERE id = :id AND estado = 'PENDIENTE'";
        try {
            $stmt_get = $this->conn->prepare($query_get); // CORRECCIÓN 11: De $this.conn a $this->conn
            $stmt_get->bindParam(':id', $movimiento_id, PDO::PARAM_INT);
            $stmt_get->execute();
            $movimiento = $stmt_get->fetch(PDO::FETCH_ASSOC);
            if (!$movimiento) {
                return ['success' => false, 'message' => 'Movimiento no encontrado o ya fue procesado.'];
            }
        } catch (PDOException $e) {
             return ['success' => false, 'message' => 'Error al buscar el movimiento: ' . $e->getMessage()];
        }
        $this->conn->beginTransaction();
        try {
            $stock_origen = $this->getInventoryStock($movimiento['producto_id'], $movimiento['almacen_origen_id']);
            if ($stock_origen < $movimiento['cantidad']) {
                throw new Exception('Stock insuficiente en origen. El stock cambió desde la solicitud. Stock actual: ' . $stock_origen);
            }
            $query_origen = "UPDATE inventario 
                             SET cantidad_actual = cantidad_actual - :cantidad 
                             WHERE producto_id = :producto_id AND almacen_id = :almacen_id";
            $stmt_origen = $this->conn->prepare($query_origen); // CORRECCIÓN 12: De $this.conn a $this->conn
            $stmt_origen->execute([
                'cantidad' => $movimiento['cantidad'],
                'producto_id' => $movimiento['producto_id'],
                'almacen_id' => $movimiento['almacen_origen_id']
            ]);
            $query_destino = "INSERT INTO inventario (producto_id, almacen_id, cantidad_actual)
                              VALUES (:producto_id, :almacen_id, :cantidad)
                              ON DUPLICATE KEY UPDATE
                              cantidad_actual = cantidad_actual + :cantidad";
            $stmt_destino = $this->conn->prepare($query_destino); // CORRECCIÓN 13: De $this.conn a $this->conn
            $stmt_destino->execute([
                'producto_id' => $movimiento['producto_id'],
                'almacen_id' => $almacen_destino_id,
                'cantidad' => $movimiento['cantidad']
            ]);
            $query_mov = "UPDATE movimientos_stock 
                          SET estado = 'APROBADO', 
                              usuario_aprobacion_id = :aprobador_id, 
                              fecha_aprobacion = NOW()
                          WHERE id = :movimiento_id";
            $stmt_mov = $this->conn->prepare($query_mov); // CORRECCIÓN 14: De $this.conn a $this->conn
            $stmt_mov->execute([
                'aprobador_id' => $aprobador_id,
                'movimiento_id' => $movimiento_id
            ]);
            $this->conn->commit();
            return ['success' => true, 'message' => 'Movimiento ID ' . $movimiento_id . ' aprobado exitosamente. El stock ha sido actualizado.'];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Error durante la transacción: ' . $e->getMessage()];
        }
    }
    public function getCurrentInventory() {
        $query = "SELECT 
                    i.cantidad_actual,
                    p.sku,
                    p.nombre AS producto_nombre,
                    a.nombre AS almacen_nombre
                  FROM inventario i
                  JOIN productos p ON i.producto_id = p.id
                  JOIN almacenes a ON i.almacen_id = a.id
                  WHERE i.cantidad_actual > 0
                  ORDER BY p.nombre ASC, a.nombre ASC";
        try {
            $stmt = $this->conn->prepare($query); // CORRECCIÓN 15: De $this.conn a $this->conn
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getCurrentInventory: ' . (string)$e->getMessage());
            return [];
        }
    }
}
// CORRECCIÓN 16: Se eliminó un '}' que aparecía al final en el archivo original.
<?php
// sgal/core/Finance.php

require_once __DIR__ . '/../config/db.php';

class Finance {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // ... (otros métodos) ...
    
    /**
     * Obtiene todos los vehículos (para el formulario de gastos).
     * @return array
     */
    public function getAllVehiculos() {
        // --- CORRECCIÓN ---
        // Se añadió `anio` a la consulta SELECT
        $query = "SELECT id, placa, marca, modelo, anio FROM vehiculos ORDER BY placa ASC";
        // --- FIN CORRECIÓN ---
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllVehiculos: ' . (string)$e->getMessage());
            return [];
        }
    }

    // ... (EL RESTO DE LOS MÉTODOS DE FINANCE.PHP) ...
    // (createGasto, getAllGastos, getGastoById, updateGasto, deleteGasto, etc.)
    // (los pego todos para que no haya dudas)

    public function getAllTiposGasto() {
        $query = "SELECT * FROM tipos_gasto ORDER BY nombre ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllTiposGasto: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getAllRutas() {
        $query = "SELECT id, nombre_ruta, fecha_planificada FROM rutas ORDER BY fecha_planificada DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllRutas: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getAllClientes() {
        $query = "SELECT id, nombre_cliente FROM clientes ORDER BY nombre_cliente ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllClientes: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function createGasto($tipo_gasto_id, $monto, $fecha, $descripcion, $vehiculo_id, $ruta_id, $usuario_registro_id) {
        $query = "INSERT INTO gastos 
                  (tipo_gasto_id, monto, fecha, descripcion, vehiculo_id, ruta_id, usuario_registro_id) 
                  VALUES 
                  (:tipo_gasto_id, :monto, :fecha, :descripcion, :vehiculo_id, :ruta_id, :usuario_registro_id)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tipo_gasto_id', $tipo_gasto_id, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':vehiculo_id', $vehiculo_id, $vehiculo_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindParam(':ruta_id', $ruta_id, $ruta_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindParam(':usuario_registro_id', $usuario_registro_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createGasto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllGastos() {
        $query = "SELECT 
                    g.id, g.monto, g.fecha, g.descripcion,
                    tg.nombre AS tipo_gasto,
                    v.placa AS vehiculo_placa,
                    r.nombre_ruta AS ruta_nombre,
                    u.nombre_completo AS usuario_registro
                  FROM gastos g
                  JOIN tipos_gasto tg ON g.tipo_gasto_id = tg.id
                  JOIN usuarios u ON g.usuario_registro_id = u.id
                  LEFT JOIN vehiculos v ON g.vehiculo_id = v.id
                  LEFT JOIN rutas r ON g.ruta_id = r.id
                  ORDER BY g.fecha DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllGastos: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getGastoById($id) {
        $query = "SELECT * FROM gastos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getGastoById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateGasto($id, $tipo_gasto_id, $monto, $fecha, $descripcion, $vehiculo_id, $ruta_id) {
        $query = "UPDATE gastos SET
                  tipo_gasto_id = :tipo_gasto_id,
                  monto = :monto,
                  fecha = :fecha,
                  descripcion = :descripcion,
                  vehiculo_id = :vehiculo_id,
                  ruta_id = :ruta_id
                  WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_gasto_id', $tipo_gasto_id, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':vehiculo_id', $vehiculo_id, $vehiculo_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindParam(':ruta_id', $ruta_id, $ruta_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateGasto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteGasto($id) {
        $query = "DELETE FROM gastos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteGasto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function createFactura($cliente_id, $numero_factura, $fecha, $monto, $estado) {
        $query = "INSERT INTO facturacion 
                  (cliente_id, numero_factura, fecha, monto, estado) 
                  VALUES 
                  (:cliente_id, :numero_factura, :fecha, :monto, :estado)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->bindParam(':numero_factura', $numero_factura);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':estado', $estado);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createFactura: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllFacturas() {
        $query = "SELECT 
                    f.id, f.numero_factura, f.fecha, f.monto, f.estado, f.fecha_pago,
                    c.nombre_cliente
                  FROM facturacion f
                  JOIN clientes c ON f.cliente_id = c.id
                  ORDER BY f.fecha DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllFacturas: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getFacturaById($id) {
        $query = "SELECT * FROM facturacion WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getFacturaById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateFactura($id, $cliente_id, $numero_factura, $fecha, $monto, $estado, $fecha_pago) {
        $query = "UPDATE facturacion SET
                  cliente_id = :cliente_id,
                  numero_factura = :numero_factura,
                  fecha = :fecha,
                  monto = :monto,
                  estado = :estado,
                  fecha_pago = :fecha_pago
                  WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->bindParam(':numero_factura', $numero_factura);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':monto', $monto);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':fecha_pago', $fecha_pago, $fecha_pago ? PDO::PARAM_STR : PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateFactura: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteFactura($id) {
        $query = "DELETE FROM facturacion WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteFactura: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function createPresupuesto($nombre, $mes, $anio, $monto) {
        $query = "INSERT INTO presupuestos 
                  (nombre, mes, anio, monto_presupuestado) 
                  VALUES 
                  (:nombre, :mes, :anio, :monto)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createPresupuesto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllPresupuestos() {
        $query = "SELECT * FROM presupuestos ORDER BY anio DESC, mes DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllPresupuestos: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getPresupuestoById($id) {
        $query = "SELECT * FROM presupuestos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getPresupuestoById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updatePresupuesto($id, $nombre, $mes, $anio, $monto) {
        $query = "UPDATE presupuestos SET
                  nombre = :nombre,
                  mes = :mes,
                  anio = :anio,
                  monto_presupuestado = :monto
                  WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updatePresupuesto: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deletePresupuesto($id) {
        $query = "DELETE FROM presupuestos WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deletePresupuesto: ' . (string)$e->getMessage());
            return false;
        }
    }
}
<?php
// sgal/core/Route.php

require_once __DIR__ . '/../config/db.php';

class Route {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // -----------------------------------------------------------------
    // MÉTODOS AUXILIARES (para <select> en formularios)
    // -----------------------------------------------------------------

    /**
     * Obtiene todos los vehículos (para el formulario de rutas).
     * @return array
     */
    public function getVehiculosDisponibles() {
        // --- CORRECCIÓN ---
        // Se añadió `anio` a la consulta SELECT
        $query = "SELECT id, placa, marca, modelo, anio FROM vehiculos ORDER BY placa ASC";
        // --- FIN CORRECIÓN ---
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getVehiculosDisponibles: ' . (string)$e->getMessage());
            return [];
        }
    }

    // ... (EL RESTO DE LOS MÉTODOS DE ROUTE.PHP) ...
    // (los pego todos para que no haya dudas)

    public function getConductoresDisponibles() {
        $query = "SELECT id, nombre_completo FROM usuarios WHERE activo = 1 ORDER BY nombre_completo ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getConductoresDisponibles: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getClientes() {
        $query = "SELECT id, nombre_cliente, direccion_principal FROM clientes ORDER BY nombre_cliente ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getClientes: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function createRoute($nombre_ruta, $fecha_planificada, $vehiculo_id, $conductor_id) {
        $query = "INSERT INTO rutas 
                  (nombre_ruta, fecha_planificada, vehiculo_id, conductor_id, estado) 
                  VALUES 
                  (:nombre_ruta, :fecha_planificada, :vehiculo_id, :conductor_id, 'PLANIFICADA')";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_ruta', $nombre_ruta);
            $stmt->bindParam(':fecha_planificada', $fecha_planificada);
            $stmt->bindParam(':vehiculo_id', $vehiculo_id, PDO::PARAM_INT);
            $stmt->bindParam(':conductor_id', $conductor_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Error en createRoute: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllRutas() {
        $query = "SELECT 
                    r.id, r.nombre_ruta, r.fecha_planificada, r.estado,
                    v.placa AS vehiculo_placa,
                    u.nombre_completo AS conductor_nombre
                  FROM rutas r
                  JOIN vehiculos v ON r.vehiculo_id = v.id
                  JOIN usuarios u ON r.conductor_id = u.id
                  ORDER BY r.fecha_planificada DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllRutas: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getRouteById($id) {
        $query = "SELECT * FROM rutas WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getRouteById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateRoute($id, $nombre_ruta, $fecha_planificada, $vehiculo_id, $conductor_id, $estado) {
        $query = "UPDATE rutas SET
                  nombre_ruta = :nombre_ruta,
                  fecha_planificada = :fecha_planificada,
                  vehiculo_id = :vehiculo_id,
                  conductor_id = :conductor_id,
                  estado = :estado
                  WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_ruta', $nombre_ruta);
            $stmt->bindParam(':fecha_planificada', $fecha_planificada);
            $stmt->bindParam(':vehiculo_id', $vehiculo_id, PDO::PARAM_INT);
            $stmt->bindParam(':conductor_id', $conductor_id, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateRoute: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getParadasByRouteId($ruta_id) {
        $query = "SELECT 
                    pr.id, pr.orden_visita, pr.estado_parada, pr.direccion,
                    c.nombre_cliente
                  FROM paradas_ruta pr
                  JOIN clientes c ON pr.cliente_id = c.id
                  WHERE pr.ruta_id = :ruta_id
                  ORDER BY pr.orden_visita ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ruta_id', $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getParadasByRouteId: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function addParadaToRoute($ruta_id, $cliente_id, $direccion, $orden_visita) {
        $query = "INSERT INTO paradas_ruta
                  (ruta_id, cliente_id, direccion, orden_visita, estado_parada)
                  VALUES
                  (:ruta_id, :cliente_id, :direccion, :orden_visita, 'PENDIENTE')";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ruta_id', $ruta_id, PDO::PARAM_INT);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':orden_visita', $orden_visita, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en addParadaToRoute: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteParada($parada_id) {
        $query = "DELETE FROM paradas_ruta WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $parada_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteParada: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getActiveRoutes() {
        $query = "SELECT 
                    r.id, r.nombre_ruta, r.fecha_planificada, r.estado,
                    v.placa AS vehiculo_placa,
                    u.nombre_completo AS conductor_nombre
                  FROM rutas r
                  JOIN vehiculos v ON r.vehiculo_id = v.id
                  JOIN usuarios u ON r.conductor_id = u.id
                  WHERE r.estado IN ('PLANIFICADA', 'EN_CURSO')
                  ORDER BY r.fecha_planificada ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getActiveRoutes: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function estimateAndSaveRouteCosts($ruta_id) {
        $costo_combustible = rand(100, 500) / 10.0;
        $costo_peajes = rand(50, 200) / 10.0;
        $costo_total = $costo_combustible + $costo_peajes;
        $query = "INSERT INTO costos_predictivos 
                  (ruta_id, costo_estimado_combustible, costo_estimado_peajes, costo_estimado_total)
                  VALUES
                  (:ruta_id, :costo_combustible, :costo_peajes, :costo_total)
                  ON DUPLICATE KEY UPDATE
                  costo_estimado_combustible = :costo_combustible,
                  costo_estimado_peajes = :costo_peajes,
                  costo_estimado_total = :costo_total";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':ruta_id', $ruta_id, PDO::PARAM_INT);
            $stmt->bindParam(':costo_combustible', $costo_combustible);
            $stmt->bindParam(':costo_peajes', $costo_peajes);
            $stmt->bindParam(':costo_total', $costo_total);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en estimateAndSaveRouteCosts: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getCosteosRutas() {
        $query = "SELECT
                    r.nombre_ruta, r.fecha_planificada,
                    c.costo_estimado_combustible, c.costo_estimado_peajes, c.costo_estimado_total
                  FROM costos_predictivos c
                  JOIN rutas r ON c.ruta_id = r.id
                  ORDER BY r.fecha_planificada DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getCosteosRutas: ' . (string)$e->getMessage());
            return [];
        }
    }
}
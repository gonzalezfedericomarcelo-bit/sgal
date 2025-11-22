<?php
// sgal/core/Analytics.php

require_once __DIR__ . '/../config/db.php';

class Analytics {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect(); // CORRECCIÓN 1: De $this.conn a $this->conn
    }

    /**
     * Obtiene los KPIs globales para el dashboard principal.
     * @return array
     */
    public function getDashboardKPIs() {
        $query = "
            SELECT
                (SELECT COUNT(*) FROM usuarios WHERE activo = 1) AS total_usuarios,
                (SELECT COUNT(*) FROM vehiculos) AS total_vehiculos,
                (SELECT COUNT(*) FROM clientes) AS total_clientes,
                (SELECT COUNT(*) FROM rutas WHERE estado = 'EN_CURSO') AS rutas_en_curso,
                (SELECT COALESCE(SUM(monto), 0) FROM facturacion WHERE estado = 'PENDIENTE') AS facturas_pendientes_monto,
                (SELECT COUNT(*) FROM almacenes) AS total_almacenes
        ";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $data['facturas_pendientes_monto'] = (float)$data['facturas_pendientes_monto'];
            return ['success' => true, 'data' => $data];
        } catch (PDOException $e) {
            error_log('Error en getDashboardKPIs: ' . (string)$e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function getDataQualityMetrics() {
        $metrics = [];
        try {
            $stmt1 = $this->conn->prepare("SELECT 
                (SELECT COUNT(*) FROM accesos WHERE fecha_salida IS NULL) AS abiertos,
                (SELECT COUNT(*) FROM accesos) AS total
            ");
            $stmt1->execute();
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            if ($result1['total'] > 0) {
                $metrics['accesos_sin_salida_count'] = (int)$result1['abiertos'];
                $metrics['accesos_sin_salida_percent'] = round(((int)$result1['abiertos'] / (int)$result1['total']) * 100, 2);
            } else {
                $metrics['accesos_sin_salida_count'] = 0;
                $metrics['accesos_sin_salida_percent'] = 0;
            }
            $stmt2 = $this->conn->prepare("SELECT 
                (SELECT COUNT(*) FROM movimientos_stock WHERE estado = 'PENDIENTE' AND fecha_solicitud < DATE_SUB(NOW(), INTERVAL 48 HOUR)) AS antiguos,
                (SELECT COUNT(*) FROM movimientos_stock WHERE estado = 'PENDIENTE') AS total_pendientes
            ");
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($result2['total_pendientes'] > 0) {
                $metrics['movimientos_antiguos_count'] = (int)$result2['antiguos'];
                $metrics['movimientos_antiguos_percent'] = round(((int)$result2['antiguos'] / (int)$result2['total_pendientes']) * 100, 2);
            } else {
                $metrics['movimientos_antiguos_count'] = 0;
                $metrics['movimientos_antiguos_percent'] = 0;
            }
            $stmt3 = $this->conn->prepare("SELECT COUNT(*) AS vencidos FROM documentos WHERE fecha_vencimiento < CURDATE()");
            $stmt3->execute();
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $metrics['documentos_vencidos_count'] = (int)$result3['vencidos'];
            return ['success' => true, 'data' => $metrics];
        } catch (PDOException $e) {
            error_log('Error en getDataQualityMetrics: ' . (string)$e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getGastosVsPresupuesto() {
        $query = "
            (SELECT 
                COALESCE(g.anio, p.anio) as anio,
                COALESCE(g.mes, p.mes) as mes,
                COALESCE(g.total_gastos, 0) as gastos,
                COALESCE(p.total_presupuesto, 0) as presupuesto
            FROM
                (SELECT YEAR(fecha) as anio, MONTH(fecha) as mes, SUM(monto) as total_gastos FROM gastos GROUP BY anio, mes) g
            LEFT JOIN
                (SELECT anio, mes, SUM(monto_presupuestado) as total_presupuesto FROM presupuestos GROUP BY anio, mes) p
                ON g.anio = p.anio AND g.mes = p.mes)
            UNION
            (SELECT 
                COALESCE(g.anio, p.anio) as anio,
                COALESCE(g.mes, p.mes) as mes,
                COALESCE(g.total_gastos, 0) as gastos,
                COALESCE(p.total_presupuesto, 0) as presupuesto
            FROM
                (SELECT YEAR(fecha) as anio, MONTH(fecha) as mes, SUM(monto) as total_gastos FROM gastos GROUP BY anio, mes) g
            RIGHT JOIN
                (SELECT anio, mes, SUM(monto_presupuestado) as total_presupuesto FROM presupuestos GROUP BY anio, mes) p
                ON g.anio = p.anio AND g.mes = p.mes)
            ORDER BY anio, mes
            LIMIT 12;
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermanenciaData() {
        $query = "SELECT 
                    tipo, 
                    AVG(TIMESTAMPDIFF(MINUTE, fecha_entrada, fecha_salida)) as avg_minutos
                  FROM accesos 
                  WHERE fecha_salida IS NOT NULL
                  GROUP BY tipo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRotacionData() {
        $query = "SELECT 
                    p.nombre,
                    COUNT(ms.id) as total_movimientos
                  FROM movimientos_stock ms
                  JOIN productos p ON ms.producto_id = p.id
                  WHERE ms.estado = 'APROBADO'
                  GROUP BY p.nombre
                  ORDER BY total_movimientos DESC
                  LIMIT 5";
        $stmt = $this->conn->prepare($query); // CORRECCIÓN 2: De $this.conn a $this->conn
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDataQualityRanking() {
        $query = "SELECT 
                    nombre_completo, 
                    SUM(acciones) as total_acciones
                  FROM (
                      (SELECT usuario_registro_id as user_id, COUNT(*) as acciones FROM accesos GROUP BY user_id)
                      UNION ALL
                      (SELECT usuario_registro_id as user_id, COUNT(*) as acciones FROM gastos GROUP BY user_id)
                      UNION ALL
                      (SELECT usuario_aprobacion_id as user_id, COUNT(*) as acciones FROM movimientos_stock WHERE estado = 'APROBADO' GROUP BY user_id)
                      UNION ALL
                      (SELECT usuario_carga_id as user_id, COUNT(*) as acciones FROM documentos GROUP BY user_id)
                  ) as subquery
                  JOIN usuarios u ON subquery.user_id = u.id
                  WHERE user_id IS NOT NULL
                  GROUP BY nombre_completo
                  ORDER BY total_acciones DESC
                  LIMIT 10";
        $stmt = $this->conn->prepare($query); // CORRECCIÓN 3: De $this.conn a $this->conn
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
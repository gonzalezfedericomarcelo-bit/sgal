<?php
// sgal/index.php

require_once 'templates/header.php';
require_once 'templates/sidebar.php'; // Incluimos el sidebar (ahora sin <main>)

// --- Carga de Datos para los Widgets ---
require_once 'core/Analytics.php';
require_once 'core/Access.php';
require_once 'core/Logistics.php';

// Creamos las instancias
$analyticsHandler = new Analytics();
$accessHandler = new Access();
$logisticsHandler = new Logistics();

// --- ¡¡¡INICIO DE LA MODIFICACIÓN!!! ---
// 1. Obtenemos los KPIs de Calidad de Datos (los que ya teníamos)
$kpi_quality_result = $analyticsHandler->getDataQualityMetrics();
$kpis_quality = $kpi_quality_result['success'] ? $kpi_quality_result['data'] : [
    'accesos_sin_salida_count' => 0,
    'movimientos_antiguos_count' => 0,
    'documentos_vencidos_count' => 0
];

// 2. Obtenemos los KPIs GLOBALES (los nuevos que pediste)
$kpi_global_result = $analyticsHandler->getDashboardKPIs();
$kpis_global = $kpi_global_result['success'] ? $kpi_global_result['data'] : [
    'total_usuarios' => 0,
    'total_vehiculos' => 0,
    'total_clientes' => 0,
    'rutas_en_curso' => 0,
    'facturas_pendientes_monto' => 0,
    'total_almacenes' => 0
];
// --- FIN DE LA MODIFICACIÓN ---


// 3. Obtenemos las listas (y las cortamos a 5)
$ultimos_accesos = array_slice($accessHandler->getHistorialAccesos(), 0, 5);
$ultimos_pendientes = array_slice($logisticsHandler->getPendingMovements(), 0, 5);

?>

<main class="content">
    
    <div class="container-fluid">
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <div class="d-none d-sm-block">
                <a href="/sgal/modules/acceso/registrar_acceso.php" class="btn btn-primary shadow-sm">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrar Acceso
                </a>
                <a href="/sgal/modules/logistica/solicitar_movimiento.php" class="btn btn-success shadow-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Solicitar Movimiento
                </a>
                <a href="/sgal/modules/rutas/planificar_ruta.php" class="btn btn-info shadow-sm">
                    <i class="bi bi-sign-turn-right-fill me-1"></i> Planificar Ruta
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-primary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Accesos Abiertos</div>
                                <div class="kpi-value"><?php echo $kpis_quality['accesos_sin_salida_count']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-door-open-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-warning text-dark">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Movimientos Pendientes (+48hs)</div>
                                <div class="kpi-value"><?php echo $kpis_quality['movimientos_antiguos_count']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clock-history kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-danger">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Documentos Vencidos</div>
                                <div class="kpi-value"><?php echo $kpis_quality['documentos_vencidos_count']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-success">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Facturación Pendiente</div>
                                <div class="kpi-value" style="font-size: 2.1rem;">$<?php echo number_format($kpis_global['facturas_pendientes_monto'], 0, ',', '.'); ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-stack kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Rutas en Curso</div>
                                <div class="kpi-value"><?php echo $kpis_global['rutas_en_curso']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-truck-front-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-secondary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Vehículos en Flota</div>
                                <div class="kpi-value"><?php echo $kpis_global['total_vehiculos']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-key-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card" style="background-color: #343a40;"> <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Total Clientes</div>
                                <div class="kpi-value"><?php echo $kpis_global['total_clientes']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-person-badge-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card" style="background-color: #004d40;"> <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Total Almacenes</div>
                                <div class="kpi-value"><?php echo $kpis_global['total_almacenes']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-buildings-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card" style="background-color: #616161;"> <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Usuarios Activos</div>
                                <div class="kpi-value"><?php echo $kpis_global['total_usuarios']; ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="row">

            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Últimos Accesos</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead>
                                    <tr><th>Tipo</th><th>Nombre/Placa</th><th>Fecha Entrada</th><th>Estado</th></tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($ultimos_accesos)): ?>
                                        <tr><td colspan="4" class="text-center text-muted p-4">No hay accesos registrados.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($ultimos_accesos as $acceso): ?>
                                    <tr>
                                        <td>
                                            <?php if ($acceso['tipo'] == 'VISITA'): ?>
                                                <span class="badge bg-info">VISITA</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">VEHICULO</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($acceso['nombre_placa']); ?></td>
                                        <td><?php echo htmlspecialchars($acceso['fecha_entrada']); ?></td>
                                        <td>
                                            <?php if ($acceso['fecha_salida']): ?>
                                                <span class="badge bg-light text-dark">Cerrado</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">DENTRO</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/sgal/modules/acceso/historial_accesos.php" class="text-decoration-none">Ver Historial Completo &raquo;</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="h5 mb-0">Movimientos Pendientes</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php if (empty($ultimos_pendientes)): ?>
                                <li class="list-group-item text-muted text-center p-3">No hay movimientos pendientes.</li>
                            <?php endif; ?>
                            <?php foreach ($ultimos_pendientes as $mov): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $mov['cantidad']; ?> x <?php echo htmlspecialchars($mov['producto_nombre']); ?></strong>
                                    <small class="d-block text-muted">Solicita: <?php echo htmlspecialchars($mov['usuario_solicitud']); ?></small>
                                </div>
                                <a href="/sgal/modules/logistica/aprobar_movimientos.php" class="btn btn-sm btn-outline-primary">Revisar</a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div> </div> </main>
<?php
// 4. Incluimos el footer
require_once 'templates/footer.php';
?>
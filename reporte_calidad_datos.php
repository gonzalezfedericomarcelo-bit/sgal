<?php
// sgal/modules/reportes/reporte_calidad_datos.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php'; // Incluimos el sidebar
require_once __DIR__ . '/../../core/Analytics.php';

Auth::enforcePermission('reportes_ver_calidad_datos');

$analyticsHandler = new Analytics();

$result = $analyticsHandler->getDataQualityMetrics();
$metrics = [];
if ($result['success']) {
    $metrics = $result['data'];
} else {
    $error_msg = $result['message'];
}
?>

<main class="content">
    
    <h1 class="h2 mb-4">Reporte de Calidad del Dato (Data Quality)</h1>
    <p class="text-muted">Métricas clave que indican la "salud" de los datos del sistema.</p>

    <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php else: ?>
        
        <div class="row">
            
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card kpi-card bg-primary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="kpi-label">Accesos Sin Cierre (Abiertos)</div>
                                <div class="kpi-value"><?php echo $metrics['accesos_sin_salida_count']; ?></div>
                                <div class="kpi-context mt-2" style="color: rgba(255,255,255,0.8);">
                                    Representa el <strong><?php echo $metrics['accesos_sin_salida_percent']; ?>%</strong> del total.
                                </div>
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
                                <div class="kpi-value"><?php echo $metrics['movimientos_antiguos_count']; ?></div>
                                 <div class="kpi-context mt-2">
                                    Representa el <strong><?php echo $metrics['movimientos_antiguos_percent']; ?>%</strong> de pendientes.
                                 </div>
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
                                <div class="kpi-value"><?php echo $metrics['documentos_vencidos_count']; ?></div>
                                <div class="kpi-context mt-2" style="color: rgba(255,255,255,0.8);">
                                    Total de documentos con fecha pasada.
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle-fill kpi-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        <?php endif; ?>
</main>
<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
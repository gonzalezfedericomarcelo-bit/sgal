<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Logistics.php';

Auth::enforcePermission('logistica_aprobar_movimiento');
$logisticsHandler = new Logistics();
$mensaje = ''; $mensaje_tipo = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'approve' && isset($_GET['id'])) {
    $movimiento_id = $_GET['id'];
    $aprobador_id = $_SESSION['user_id'];
    $resultado = $logisticsHandler->approveMovement($movimiento_id, $aprobador_id);
    if ($resultado['success']) {
        $mensaje = $resultado['message'];
    } else {
        $mensaje = $resultado['message']; $mensaje_tipo = 'danger';
    }
}
$lista_pendientes = $logisticsHandler->getPendingMovements();
?>

<main class="content">
    <h1 class="h2 mb-4">Aprobar Movimientos de Stock</h1>
    <p class="text-muted">Lista de solicitudes pendientes de aprobación.</p>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Cant.</th>
                            <th scope="col">Desde (Origen)</th>
                            <th scope="col">Hacia (Destino)</th>
                            <th scope="col">Solicitante</th>
                            <th scope="col">Fecha Solicitud</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_pendientes)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">No hay movimientos pendientes de aprobación.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lista_pendientes as $mov): ?>
                                <tr>
                                    <td><?php echo $mov['id']; ?></td>
                                    <td><?php echo htmlspecialchars($mov['producto_nombre']); ?></td>
                                    <td><strong class="text-primary"><?php echo $mov['cantidad']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($mov['almacen_origen']); ?></td>
                                    <td><?php echo htmlspecialchars($mov['almacen_destino']); ?></td>
                                    <td><?php echo htmlspecialchars($mov['usuario_solicitud']); ?></td>
                                    <td><?php echo $mov['fecha_solicitud']; ?></td>
                                    <td>
                                        <a href="aprobar_movimientos.php?action=approve&id=<?php echo $mov['id']; ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('¿Está seguro de que desea aprobar este movimiento de stock? Esta acción es irreversible.');">
                                           <i class="bi bi-check-circle-fill me-1"></i> Aprobar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
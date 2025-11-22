<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Logistics.php';

Auth::enforcePermission('logistica_solicitar_movimiento');
$logisticsHandler = new Logistics();
$mensaje = ''; $mensaje_tipo = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'solicitar') {
    $producto_id = $_POST['producto_id'];
    $almacen_origen_id = $_POST['almacen_origen_id'];
    $almacen_destino_id = $_POST['almacen_destino_id'];
    $cantidad = $_POST['cantidad'];
    $usuario_solicitud_id = $_SESSION['user_id'];
    $resultado = $logisticsHandler->requestMovement($producto_id, $almacen_origen_id, $almacen_destino_id, $cantidad, $usuario_solicitud_id);
    if ($resultado['success']) {
        $mensaje = $resultado['message'];
    } else {
        $mensaje = $resultado['message']; $mensaje_tipo = 'danger';
    }
}
$lista_productos = $logisticsHandler->getAllProductos();
$lista_almacenes = $logisticsHandler->getAllAlmacenes();
?>

<main class="content">
    <h1 class="h2 mb-4">Solicitar Movimiento de Stock</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-box-arrow-up-right me-2"></i>Nueva Solicitud</h3>
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">El stock disponible en el almacén de origen será verificado. La solicitud quedará pendiente de aprobación (o se aprobará automáticamente según la configuración global).</p>
                    
                    <form action="solicitar_movimiento.php" method="POST">
                        <input type="hidden" name="action" value="solicitar">
                        
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Producto</label>
                            <select class="form-select" id="producto_id" name="producto_id" required>
                                <option value="">-- Seleccione un producto --</option>
                                <?php foreach ($lista_productos as $producto): ?>
                                    <option value="<?php echo $producto['id']; ?>">
                                        <?php echo htmlspecialchars($producto['sku'] . ' - ' . $producto['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="almacen_origen_id" class="form-label">Mover DESDE (Origen)</label>
                                <select class="form-select" id="almacen_origen_id" name="almacen_origen_id" required>
                                    <option value="">-- Seleccione un almacén --</option>
                                    <?php foreach ($lista_almacenes as $almacen): ?>
                                        <option value="<?php echo $almacen['id']; ?>">
                                            <?php echo htmlspecialchars($almacen['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="almacen_destino_id" class="form-label">Mover HACIA (Destino)</label>
                                <select class="form-select" id="almacen_destino_id" name="almacen_destino_id" required>
                                    <option value="">-- Seleccione un almacén --</option>
                                    <?php foreach ($lista_almacenes as $almacen): ?>
                                        <option value="<?php echo $almacen['id']; ?>">
                                            <?php echo htmlspecialchars($almacen['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad a Mover</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                        </div>

                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send-fill me-1"></i>Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
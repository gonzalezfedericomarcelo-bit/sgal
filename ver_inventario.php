<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Logistics.php';

Auth::enforcePermission('logistica_ver_inventario');
$logisticsHandler = new Logistics();
$inventario_actual = $logisticsHandler->getCurrentInventory();
?>

<main class="content">
    <h1 class="h2 mb-4">Inventario de Stock Actual</h1>
    <p class="text-muted">Reporte de cantidades actuales por producto y almacén (solo se muestran productos con stock > 0).</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">SKU</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Almacén</th>
                            <th scope="col" class="text-end">Cantidad Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($inventario_actual)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">Actualmente no hay stock registrado en el inventario.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($inventario_actual as $item): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($item['sku']); ?></span></td>
                                    <td><?php echo htmlspecialchars($item['producto_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($item['almacen_nombre']); ?></td>
                                    <td class="text-end">
                                        <strong class="fs-5 text-primary"><?php echo $item['cantidad_actual']; ?></strong>
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
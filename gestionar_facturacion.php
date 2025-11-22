<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Finance.php';

Auth::enforcePermission('financiero_gestionar_facturacion');
$financeHandler = new Finance();
$mensaje = ''; $mensaje_tipo = 'success';
$edit_factura = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $cliente_id = $_POST['cliente_id'];
    $numero_factura = $_POST['numero_factura'];
    $fecha = $_POST['fecha'];
    $monto = $_POST['monto'];
    $estado = $_POST['estado'];
    $fecha_pago = !empty($_POST['fecha_pago']) ? $_POST['fecha_pago'] : null;
    if ($estado == 'PAGADA' && empty($fecha_pago)) { $fecha_pago = date('Y-m-d'); }
    if ($estado == 'PENDIENTE') { $fecha_pago = null; }
    try {
        if ($_POST['action'] == 'create') {
            if ($financeHandler->createFactura($cliente_id, $numero_factura, $fecha, $monto, $estado)) {
                $mensaje = "Factura registrada exitosamente.";
            } else {
                throw new Exception("Error al registrar la factura.");
            }
        } 
        elseif ($_POST['action'] == 'update' && isset($_POST['factura_id'])) {
            $id = $_POST['factura_id'];
            if ($financeHandler->updateFactura($id, $cliente_id, $numero_factura, $fecha, $monto, $estado, $fecha_pago)) {
                $mensaje = "Factura actualizada exitosamente.";
            } else {
                throw new Exception("Error al actualizar la factura.");
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage(); $mensaje_tipo = 'danger';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($financeHandler->deleteFactura($_GET['id'])) {
            $mensaje = "Factura eliminada exitosamente.";
        } else {
            $mensaje = "Error al eliminar la factura."; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_factura = $financeHandler->getFacturaById($_GET['id']);
    }
}
$lista_facturas = $financeHandler->getAllFacturas();
$lista_clientes = $financeHandler->getAllClientes();
?>

<main class="content">
    <h1 class="h2 mb-4">Gestionar Facturación</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-receipt me-2"></i><?php echo $edit_factura ? 'Editar Factura' : 'Registrar Nueva Factura'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_facturacion.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_factura ? 'update' : 'create'; ?>">
                <?php if ($edit_factura) echo '<input type="hidden" name="factura_id" value="' . $edit_factura['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cliente_id" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="">-- Seleccione un cliente --</option>
                            <?php foreach ($lista_clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" <?php echo (isset($edit_factura) && $edit_factura['cliente_id'] == $cliente['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cliente['nombre_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="numero_factura" class="form-label">Número de Factura</label>
                        <input type="text" class="form-control" id="numero_factura" name="numero_factura" value="<?php echo $edit_factura['numero_factura'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha de Emisión</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $edit_factura['fecha'] ?? date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="monto" class="form-label">Monto ($)</label>
                        <input type="number" class="form-control" step="0.01" id="monto" name="monto" value="<?php echo $edit_factura['monto'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="PENDIENTE" <?php echo (isset($edit_factura) && $edit_factura['estado'] == 'PENDIENTE') ? 'selected' : ''; ?>>Pendiente</option>
                            <option value="PAGADA" <?php echo (isset($edit_factura) && $edit_factura['estado'] == 'PAGADA') ? 'selected' : ''; ?>>Pagada</option>
                            <option value="ANULADA" <?php echo (isset($edit_factura) && $edit_factura['estado'] == 'ANULADA') ? 'selected' : ''; ?>>Anulada</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="<?php echo $edit_factura['fecha_pago'] ?? ''; ?>">
                        <div class="form-text">Auto-completar si se marca 'Pagada' y se deja vacío.</div>
                    </div>
                </div>

                <hr class="my-4">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_factura ? 'Actualizar Factura' : 'Guardar Factura'; ?>
                </button>
                <?php if ($edit_factura) echo '<a href="gestionar_facturacion.php" class="btn btn-secondary ms-2">Cancelar</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h3 class="h5 mb-0">Historial de Facturación</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>N° Factura</th>
                            <th>Cliente</th>
                            <th>Fecha Emisión</th>
                            <th class="text-end">Monto</th>
                            <th>Estado</th>
                            <th>Fecha Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_facturas)): ?>
                            <tr><td colspan="7" class="text-center text-muted p-4">No hay facturas registradas.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lista_facturas as $factura): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($factura['numero_factura']); ?></td>
                                <td><?php echo htmlspecialchars($factura['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($factura['fecha']); ?></td>
                                <td class="text-end">$<?php echo number_format($factura['monto'], 2); ?></td>
                                <td>
                                    <?php
                                    $color = 'secondary';
                                    if ($factura['estado'] == 'PAGADA') $color = 'success';
                                    if ($factura['estado'] == 'PENDIENTE') $color = 'warning';
                                    if ($factura['estado'] == 'ANULADA') $color = 'danger';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?>"><?php echo htmlspecialchars($factura['estado']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($factura['fecha_pago'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="gestionar_facturacion.php?action=edit&id=<?php echo $factura['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="gestionar_facturacion.php?action=delete&id=<?php echo $factura['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Finance.php';

Auth::enforcePermission('financiero_gestionar_gastos');
$financeHandler = new Finance();
$mensaje = ''; $mensaje_tipo = 'success';
$edit_gasto = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $tipo_gasto_id = $_POST['tipo_gasto_id'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'] ?? '';
    $vehiculo_id = !empty($_POST['vehiculo_id']) ? $_POST['vehiculo_id'] : null;
    $ruta_id = !empty($_POST['ruta_id']) ? $_POST['ruta_id'] : null;
    try {
        if ($_POST['action'] == 'create') {
            $usuario_registro_id = $_SESSION['user_id'];
            if ($financeHandler->createGasto($tipo_gasto_id, $monto, $fecha, $descripcion, $vehiculo_id, $ruta_id, $usuario_registro_id)) {
                $mensaje = "Gasto registrado exitosamente.";
            } else {
                throw new Exception("Error al registrar el gasto.");
            }
        } 
        elseif ($_POST['action'] == 'update' && isset($_POST['gasto_id'])) {
            $id = $_POST['gasto_id'];
            if ($financeHandler->updateGasto($id, $tipo_gasto_id, $monto, $fecha, $descripcion, $vehiculo_id, $ruta_id)) {
                $mensaje = "Gasto actualizado exitosamente.";
            } else {
                throw new Exception("Error al actualizar el gasto.");
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage(); $mensaje_tipo = 'danger';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($financeHandler->deleteGasto($_GET['id'])) {
            $mensaje = "Gasto eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar el gasto."; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_gasto = $financeHandler->getGastoById($_GET['id']);
    }
}

$lista_gastos = $financeHandler->getAllGastos();
$lista_tipos_gasto = $financeHandler->getAllTiposGasto();
$lista_vehiculos = $financeHandler->getAllVehiculos();
$lista_rutas = $financeHandler->getAllRutas();
?>

<main class="content">
    <h1 class="h2 mb-4">Gestionar Gastos Operativos</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><?php echo $edit_gasto ? 'Editar Gasto' : 'Registrar Nuevo Gasto'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_gastos.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_gasto ? 'update' : 'create'; ?>">
                <?php if ($edit_gasto) echo '<input type="hidden" name="gasto_id" value="' . $edit_gasto['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="tipo_gasto_id" class="form-label">Tipo de Gasto</label>
                        <select class="form-select" id="tipo_gasto_id" name="tipo_gasto_id" required>
                            <option value="">-- Seleccione un tipo --</option>
                            <?php foreach ($lista_tipos_gasto as $tipo): ?>
                                <option value="<?php echo $tipo['id']; ?>" <?php echo (isset($edit_gasto) && $edit_gasto['tipo_gasto_id'] == $tipo['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="monto" class="form-label">Monto ($)</label>
                        <input type="number" class="form-control" step="0.01" id="monto" name="monto" value="<?php echo $edit_gasto['monto'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $edit_gasto['fecha'] ?? date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $edit_gasto['descripcion'] ?? ''; ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="vehiculo_id" class="form-label">Vincular a Vehículo (Opcional)</label>
                        <select class="form-select" id="vehiculo_id" name="vehiculo_id">
                            <option value="">-- Ninguno --</option>
                            <?php foreach ($lista_vehiculos as $vehiculo): ?>
                                <option value="<?php echo $vehiculo['id']; ?>" <?php echo (isset($edit_gasto) && $edit_gasto['vehiculo_id'] == $vehiculo['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vehiculo['placa'] . ' (' . $vehiculo['marca'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="ruta_id" class="form-label">Vincular a Ruta (Opcional)</label>
                        <select class="form-select" id="ruta_id" name="ruta_id">
                            <option value="">-- Ninguna --</option>
                            <?php foreach ($lista_rutas as $ruta): ?>
                                <option value="<?php echo $ruta['id']; ?>" <?php echo (isset($edit_gasto) && $edit_gasto['ruta_id'] == $ruta['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ruta['nombre_ruta'] . ' (' . $ruta['fecha_planificada'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <hr class="my-4">
                <button type="submit" class="btn btn-primary">
                    <?php echo $edit_gasto ? 'Actualizar Gasto' : 'Guardar Gasto'; ?>
                </button>
                <?php if ($edit_gasto) echo '<a href="gestionar_gastos.php" class="btn btn-secondary ms-2">Cancelar</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h3 class="h5 mb-0">Historial de Gastos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th class="text-end">Monto</th>
                            <th>Descripción</th>
                            <th>Vehículo</th>
                            <th>Ruta</th>
                            <th>Registrado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_gastos)): ?>
                            <tr><td colspan="8" class="text-center text-muted p-4">No hay gastos registrados.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lista_gastos as $gasto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($gasto['fecha']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['tipo_gasto']); ?></td>
                                <td class="text-end">$<?php echo number_format($gasto['monto'], 2); ?></td>
                                <td><?php echo htmlspecialchars($gasto['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($gasto['vehiculo_placa'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($gasto['ruta_nombre'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($gasto['usuario_registro']); ?></td>
                                <td>
                                    <a href="gestionar_gastos.php?action=edit&id=<?php echo $gasto['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="gestionar_gastos.php?action=delete&id=<?php echo $gasto['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash-fill"></i></a>
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
<?php
// sgal/modules/financiero/gestionar_presupuestos.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Finance.php';

Auth::enforcePermission('financiero_gestionar_presupuestos');
$financeHandler = new Finance();
$mensaje = ''; $mensaje_tipo = 'success';
$edit_presupuesto = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre'];
    $mes = $_POST['mes'];
    $anio = $_POST['anio'];
    $monto = $_POST['monto'];
    try {
        if ($_POST['action'] == 'create') {
            if ($financeHandler->createPresupuesto($nombre, $mes, $anio, $monto)) {
                $mensaje = "Presupuesto registrado exitosamente.";
            } else {
                throw new Exception("Error al registrar el presupuesto.");
            }
        } 
        elseif ($_POST['action'] == 'update' && isset($_POST['presupuesto_id'])) {
            $id = $_POST['presupuesto_id'];
            if ($financeHandler->updatePresupuesto($id, $nombre, $mes, $anio, $monto)) {
                $mensaje = "Presupuesto actualizado exitosamente.";
            } else {
                throw new Exception("Error al actualizar el presupuesto.");
            }
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage(); $mensaje_tipo = 'danger';
    }
}
// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($financeHandler->deletePresupuesto($_GET['id'])) {
            $mensaje = "Presupuesto eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar el presupuesto."; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_presupuesto = $financeHandler->getPresupuestoById($_GET['id']);
    }
}
$lista_presupuestos = $financeHandler->getAllPresupuestos();
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$anio_actual = date('Y');
?>

<main class="content">

    <h1 class="h2 mb-4">Gestionar Presupuestos Mensuales</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-calculator-fill me-2"></i><?php echo $edit_presupuesto ? 'Editar Presupuesto' : 'Registrar Nuevo Presupuesto'; ?></h3>
                </div>
                <div class="card-body">
                    <form action="gestionar_presupuestos.php" method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_presupuesto ? 'update' : 'create'; ?>">
                        <?php if ($edit_presupuesto) echo '<input type="hidden" name="presupuesto_id" value="' . $edit_presupuesto['id'] . '">'; ?>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre (Ej: "Gastos Combustible", "Gastos Generales")</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $edit_presupuesto['nombre'] ?? ''; ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mes" class="form-label">Mes</label>
                                <select class="form-select" id="mes" name="mes" required>
                                    <?php foreach ($meses as $num => $nombre_mes): ?>
                                        <option value="<?php echo $num; ?>" <?php echo (isset($edit_presupuesto) && $edit_presupuesto['mes'] == $num) ? 'selected' : (($num == date('n')) ? 'selected' : ''); ?>>
                                            <?php echo $nombre_mes; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <input type="number" class="form-control" id="anio" name="anio" min="2020" max="2040" value="<?php echo $edit_presupuesto['anio'] ?? $anio_actual; ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="monto" class="form-label">Monto Presupuestado ($)</label>
                            <input type="number" class="form-control" step="0.01" id="monto" name="monto" value="<?php echo $edit_presupuesto['monto_presupuestado'] ?? ''; ?>" required>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <?php echo $edit_presupuesto ? 'Actualizar Presupuesto' : 'Guardar Presupuesto'; ?>
                        </button>
                        <?php if ($edit_presupuesto) echo '<a href="gestionar_presupuestos.php" class="btn btn-secondary ms-2">Cancelar</a>'; ?>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-list-task me-2"></i>Presupuestos Registrados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th class="text-end">Monto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lista_presupuestos)): ?>
                                    <tr><td colspan="5" class="text-center text-muted p-4">No hay presupuestos registrados.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($lista_presupuestos as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                                        <td><?php echo $meses[$p['mes']]; ?></td>
                                        <td><?php echo $p['anio']; ?></td>
                                        <td class="text-end">$<?php echo number_format($p['monto_presupuestado'], 2); ?></td>
                                        <td>
                                            <a href="gestionar_presupuestos.php?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="gestionar_presupuestos.php?action=delete&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');"><i class="bi bi-trash-fill"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
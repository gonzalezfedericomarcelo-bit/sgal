<?php
// sgal/modules/config/gestionar_vehiculos.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Management.php';
require_once __DIR__ . '/../../core/Finance.php'; // Para la lista

Auth::enforcePermission('config_gestionar_vehiculos');

$managementHandler = new Management();
$financeHandler = new Finance();
$mensaje = '';
$mensaje_tipo = 'success';
$edit_vehiculo = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $placa = $_POST['placa'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = !empty($_POST['anio']) ? $_POST['anio'] : null;

    if ($_POST['action'] == 'create') {
        if ($managementHandler->createVehiculo($placa, $marca, $modelo, $anio)) {
            $mensaje = "Vehículo creado exitosamente.";
        } else {
            $mensaje = "Error al crear el vehículo."; $mensaje_tipo = 'danger';
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['vehiculo_id'])) {
        $id = $_POST['vehiculo_id'];
        if ($managementHandler->updateVehiculo($id, $placa, $marca, $modelo, $anio)) {
            $mensaje = "Vehículo actualizado exitosamente.";
        } else {
            $mensaje = "Error al actualizar el vehículo."; $mensaje_tipo = 'danger';
        }
    }
}

// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($managementHandler->deleteVehiculo($_GET['id'])) {
            $mensaje = "Vehículo eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar. ¿El vehículo está en uso en una ruta o gasto?"; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_vehiculo = $managementHandler->getVehiculoById($_GET['id']);
    }
}

$lista_vehiculos = $financeHandler->getAllVehiculos();
?>

<main class="content">

    <h1 class="h2 mb-4">Gestionar Flota de Vehículos</h1>
    
    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><?php echo $edit_vehiculo ? 'Editar Vehículo' : 'Añadir Nuevo Vehículo'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_vehiculos.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_vehiculo ? 'update' : 'create'; ?>">
                <?php if ($edit_vehiculo) echo '<input type="hidden" name="vehiculo_id" value="' . $edit_vehiculo['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="placa" class="form-label">Placa (Patente)</label>
                        <input type="text" class="form-control" id="placa" name="placa" value="<?php echo $edit_vehiculo['placa'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" value="<?php echo $edit_vehiculo['marca'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo $edit_vehiculo['modelo'] ?? ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="anio" class="form-label">Año</label>
                        <input type="number" class="form-control" id="anio" name="anio" min="1990" max="<?php echo date('Y') + 1; ?>" value="<?php echo $edit_vehiculo['anio'] ?? ''; ?>">
                    </div>
                </div>
                
                <hr class="my-4">

                <button type="submit" class="btn btn-primary"><?php echo $edit_vehiculo ? 'Actualizar' : 'Guardar'; ?></button>
                <?php if ($edit_vehiculo) echo '<a href="gestionar_vehiculos.php" class="btn btn-secondary ms-2">Cancelar Edición</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0">Flota Registrada</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Placa</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Año</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_vehiculos)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No hay vehículos registrados.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($lista_vehiculos as $vehiculo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vehiculo['placa']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['marca']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($vehiculo['anio']); ?></td>
                                <td>
                                    <a href="gestionar_vehiculos.php?action=edit&id=<?php echo $vehiculo['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Editar
                                    </a>
                                    <a href="gestionar_vehiculos.php?action=delete&id=<?php echo $vehiculo['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');">
                                        <i class="bi bi-trash-fill me-1"></i> Eliminar
                                    </a>
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
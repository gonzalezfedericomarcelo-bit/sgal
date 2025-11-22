<?php
// sgal/modules/config/gestionar_tipos_gasto.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Management.php';
require_once __DIR__ . '/../../core/Finance.php'; // Para la lista

Auth::enforcePermission('config_gestionar_tipos_gasto');

$managementHandler = new Management();
$financeHandler = new Finance();
$mensaje = '';
$mensaje_tipo = 'success';
$edit_tipo = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre'];

    if ($_POST['action'] == 'create') {
        if ($managementHandler->createTipoGasto($nombre)) {
            $mensaje = "Tipo de gasto creado exitosamente.";
        } else {
            $mensaje = "Error al crear el tipo de gasto."; $mensaje_tipo = 'danger';
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['tipo_id'])) {
        $id = $_POST['tipo_id'];
        if ($managementHandler->updateTipoGasto($id, $nombre)) {
            $mensaje = "Tipo de gasto actualizado exitosamente.";
        } else {
            $mensaje = "Error al actualizar el tipo de gasto."; $mensaje_tipo = 'danger';
        }
    }
}

// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($managementHandler->deleteTipoGasto($_GET['id'])) {
            $mensaje = "Tipo de gasto eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar. ¿El tipo de gasto está en uso?"; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_tipo = $managementHandler->getTipoGastoById($_GET['id']);
    }
}

$lista_tipos = $financeHandler->getAllTiposGasto();
?>

<main class="content">

    <h1 class="h2 mb-4">Gestionar Tipos de Gasto</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><?php echo $edit_tipo ? 'Editar Tipo de Gasto' : 'Añadir Nuevo Tipo'; ?></h3>
                </div>
                <div class="card-body">
                    <form action="gestionar_tipos_gasto.php" method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_tipo ? 'update' : 'create'; ?>">
                        <?php if ($edit_tipo) echo '<input type="hidden" name="tipo_id" value="' . $edit_tipo['id'] . '">'; ?>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre (Ej: Combustible, Peajes, Viáticos)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $edit_tipo['nombre'] ?? ''; ?>" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary"><?php echo $edit_tipo ? 'Actualizar' : 'Guardar'; ?></button>
                        <?php if ($edit_tipo) echo '<a href="gestionar_tipos_gasto.php" class="btn btn-secondary ms-2">Cancelar</a>'; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0">Tipos de Gasto Registrados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lista_tipos)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No hay tipos de gasto registrados.</td>
                                    </tr>
                                <?php endif; ?>
                                <?php foreach ($lista_tipos as $tipo): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                                        <td>
                                            <a href="gestionar_tipos_gasto.php?action=edit&id=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-fill me-1"></i> Editar
                                            </a>
                                            <a href="gestionar_tipos_gasto.php?action=delete&id=<?php echo $tipo['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');">
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
        </div>
    </div> </main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
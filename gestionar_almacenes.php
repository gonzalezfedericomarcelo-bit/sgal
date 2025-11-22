<?php
// sgal/modules/logistica/gestionar_almacenes.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php'; // Incluimos el sidebar (ahora sin <main>)
require_once __DIR__ . '/../../core/Logistics.php';

Auth::enforcePermission('logistica_gestionar_almacenes');

$logisticsHandler = new Logistics();
$mensaje = '';
$mensaje_tipo = 'success';
$edit_almacen = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre'];
    $ubicacion = $_POST['ubicacion'];
    if ($_POST['action'] == 'create') {
        if ($logisticsHandler->createAlmacen($nombre, $ubicacion)) {
            $mensaje = "Almacén creado exitosamente.";
        } else {
            $mensaje = "Error al crear el almacén."; $mensaje_tipo = 'danger';
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['almacen_id'])) {
        $id = $_POST['almacen_id'];
        if ($logisticsHandler->updateAlmacen($id, $nombre, $ubicacion)) {
            $mensaje = "Almacén actualizado exitosamente.";
        } else {
            $mensaje = "Error al actualizar el almacén."; $mensaje_tipo = 'danger';
        }
    }
}
// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($logisticsHandler->deleteAlmacen($_GET['id'])) {
            $mensaje = "Almacén eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar el almacén."; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_almacen = $logisticsHandler->getAlmacenById($_GET['id']);
    }
}
$lista_almacenes = $logisticsHandler->getAllAlmacenes();
?>

<main class="content">

    <h1 class="h2 mb-4">Gestionar Almacenes</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><?php echo $edit_almacen ? 'Editar Almacén' : 'Añadir Nuevo Almacén'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_almacenes.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_almacen ? 'update' : 'create'; ?>">
                <?php if ($edit_almacen) echo '<input type="hidden" name="almacen_id" value="' . $edit_almacen['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label">Nombre del Almacén</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $edit_almacen['nombre'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo $edit_almacen['ubicacion'] ?? ''; ?>">
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-primary"><?php echo $edit_almacen ? 'Actualizar' : 'Guardar'; ?></button>
                <?php if ($edit_almacen) echo '<a href="gestionar_almacenes.php" class="btn btn-secondary ms-2">Cancelar Edición</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0">Almacenes Registrados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Ubicación</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_almacenes)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay almacenes registrados.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($lista_almacenes as $almacen): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($almacen['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($almacen['ubicacion']); ?></td>
                                <td>
                                    <a href="gestionar_almacenes.php?action=edit&id=<?php echo $almacen['id']; ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                    <a href="gestionar_almacenes.php?action=delete&id=<?php echo $almacen['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
<?php
// 4. Incluimos el footer
require_once __DIR__ . '/../../templates/footer.php';
?>
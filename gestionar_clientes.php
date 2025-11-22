<?php
// sgal/modules/config/gestionar_clientes.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Management.php';
require_once __DIR__ . '/../../core/Finance.php'; // Para la lista

Auth::enforcePermission('config_gestionar_clientes');

$managementHandler = new Management();
$financeHandler = new Finance(); // Usamos esto para la lista
$mensaje = '';
$mensaje_tipo = 'success';
$edit_cliente = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre_cliente'];
    $direccion = $_POST['direccion_principal'];
    $telefono = $_POST['telefono'];

    if ($_POST['action'] == 'create') {
        if ($managementHandler->createCliente($nombre, $direccion, $telefono)) {
            $mensaje = "Cliente creado exitosamente.";
        } else {
            $mensaje = "Error al crear el cliente."; $mensaje_tipo = 'danger';
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['cliente_id'])) {
        $id = $_POST['cliente_id'];
        if ($managementHandler->updateCliente($id, $nombre, $direccion, $telefono)) {
            $mensaje = "Cliente actualizado exitosamente.";
        } else {
            $mensaje = "Error al actualizar el cliente."; $mensaje_tipo = 'danger';
        }
    }
}

// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($managementHandler->deleteCliente($_GET['id'])) {
            $mensaje = "Cliente eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar. ¿El cliente está en uso en una factura o ruta?"; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_cliente = $managementHandler->getClienteById($_GET['id']);
    }
}

$lista_clientes = $financeHandler->getAllClientes();
?>

<main class="content">

    <h1 class="h2 mb-4">Gestionar Clientes</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><?php echo $edit_cliente ? 'Editar Cliente' : 'Añadir Nuevo Cliente'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_clientes.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_cliente ? 'update' : 'create'; ?>">
                <?php if ($edit_cliente) echo '<input type="hidden" name="cliente_id" value="' . $edit_cliente['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre_cliente" class="form-label">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="<?php echo $edit_cliente['nombre_cliente'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $edit_cliente['telefono'] ?? ''; ?>">
                    </div>
                    <div class="col-12">
                        <label for="direccion_principal" class="form-label">Dirección Principal</label>
                        <input type="text" class="form-control" id="direccion_principal" name="direccion_principal" value="<?php echo $edit_cliente['direccion_principal'] ?? ''; ?>">
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-primary"><?php echo $edit_cliente ? 'Actualizar' : 'Guardar'; ?></button>
                <?php if ($edit_cliente) echo '<a href="gestionar_clientes.php" class="btn btn-secondary ms-2">Cancelar Edición</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0">Clientes Registrados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nombre Cliente</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_clientes)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No hay clientes registrados.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($lista_clientes as $cliente): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['nombre_cliente']); ?></td>
                                <td><?php echo htmlspecialchars($cliente['direccion_principal'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?></td>
                                <td>
                                    <a href="gestionar_clientes.php?action=edit&id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Editar
                                    </a>
                                    <a href="gestionar_clientes.php?action=delete&id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');">
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
<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
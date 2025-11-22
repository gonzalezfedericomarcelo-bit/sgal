<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Logistics.php';

Auth::enforcePermission('logistica_gestionar_productos');
$logisticsHandler = new Logistics();
$mensaje = ''; $mensaje_tipo = 'success';
$edit_producto = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $sku = $_POST['sku'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    if ($_POST['action'] == 'create') {
        if ($logisticsHandler->createProducto($sku, $nombre, $descripcion)) {
            $mensaje = "Producto creado exitosamente.";
        } else {
            $mensaje = "Error al crear el producto. ¿El SKU ya existe?"; $mensaje_tipo = 'danger';
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['producto_id'])) {
        $id = $_POST['producto_id'];
        if ($logisticsHandler->updateProducto($id, $sku, $nombre, $descripcion)) {
            $mensaje = "Producto actualizado exitosamente.";
        } else {
            $mensaje = "Error al actualizar el producto."; $mensaje_tipo = 'danger';
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($logisticsHandler->deleteProducto($_GET['id'])) {
            $mensaje = "Producto eliminado exitosamente.";
        } else {
            $mensaje = "Error al eliminar el producto. Asegúrese de que no tenga inventario."; $mensaje_tipo = 'danger';
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_producto = $logisticsHandler->getProductoById($_GET['id']);
    }
}
$lista_productos = $logisticsHandler->getAllProductos();
?>

<main class="content">
    <h1 class="h2 mb-4">Gestionar Productos</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><?php echo $edit_producto ? 'Editar Producto' : 'Añadir Nuevo Producto'; ?></h3>
        </div>
        <div class="card-body">
            <form action="gestionar_productos.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_producto ? 'update' : 'create'; ?>">
                <?php if ($edit_producto) echo '<input type="hidden" name="producto_id" value="' . $edit_producto['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="sku" class="form-label">SKU (Código Único)</label>
                        <input type="text" class="form-control" id="sku" name="sku" value="<?php echo $edit_producto['sku'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label for="nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $edit_producto['nombre'] ?? ''; ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $edit_producto['descripcion'] ?? ''; ?></textarea>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-primary"><?php echo $edit_producto ? 'Actualizar' : 'Guardar'; ?></button>
                <?php if ($edit_producto) echo '<a href="gestionar_productos.php" class="btn btn-secondary ms-2">Cancelar Edición</a>'; ?>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0">Catálogo de Productos</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">SKU</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_productos)): ?>
                            <tr><td colspan="4" class="text-center text-muted p-4">No hay productos registrados.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lista_productos as $producto): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?php echo htmlspecialchars($producto['sku']); ?></span></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td>
                                    <a href="gestionar_productos.php?action=edit&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Editar
                                    </a>
                                    <a href="gestionar_productos.php?action=delete&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro?');">
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
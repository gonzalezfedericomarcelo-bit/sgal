<?php
// sgal/modules/documentos/gestionar_documentos.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php'; // Incluimos el sidebar
require_once __DIR__ . '/../../core/Document.php';
require_once __DIR__ . '/../../core/Finance.php'; // ¡¡¡CORRECCIÓN 1: Incluimos Finance!!!

// 2. Seguridad: Permiso para gestionar documentos.
Auth::enforcePermission('documentos_gestionar');

$docHandler = new Document();
$financeHandler = new Finance(); // ¡¡¡CORRECCIÓN 2: Creamos la instancia de Finance!!!
$mensaje = '';
$mensaje_tipo = 'success';

// --- Lógica de POST (Solo para CREAR) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
    
    if (isset($_FILES['archivo_doc']) && $_FILES['archivo_doc']['error'] == UPLOAD_ERR_OK) {
        
        $resultado = $docHandler->createDocument(
            $_FILES['archivo_doc'],
            $_POST['nombre_doc'],
            $_POST['entidad_tipo'],
            $_POST['entidad_id'],
            !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null,
            $_SESSION['user_id']
        );
        
        if ($resultado['success']) {
            $mensaje = $resultado['message'];
        } else {
            $mensaje = $resultado['message'];
            $mensaje_tipo = 'danger';
        }
        
    } else {
        $mensaje = "Error: No se seleccionó ningún archivo o el archivo es demasiado grande.";
        $mensaje_tipo = 'danger';
    }
}

// --- Lógica de GET (Borrar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($docHandler->deleteDocument($_GET['id'])) {
        $mensaje = "Documento eliminado exitosamente (registro y archivo).";
    } else {
        $mensaje = "Error al eliminar el documento.";
        $mensaje_tipo = 'danger';
    }
}

// 4. Obtenemos datos para la vista
$lista_documentos = $docHandler->getAllDocuments();
$lista_vehiculos = $docHandler->getVehiculos();
$lista_conductores = $docHandler->getConductores();
$lista_clientes = $financeHandler->getAllClientes(); // ¡¡¡CORRECCIÓN 3: Usamos $financeHandler!!!
?>

<main class="content">

    <h1 class="h2 mb-4">Gestión Documental</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>Subir Nuevo Documento</h3>
        </div>
        <div class="card-body">
            <form action="gestionar_documentos.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre_doc" class="form-label">Nombre del Documento (Ej: VTV, Licencia, Seguro)</label>
                        <input type="text" class="form-control" id="nombre_doc" name="nombre_doc" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento (Opcional)</label>
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento">
                    </div>
                    <div class="col-md-6">
                        <label for="entidad_tipo" class="form-label">Asociar a:</label>
                        <select class="form-select" id="entidad_tipo" name="entidad_tipo" required>
                            <option value="">-- Seleccione un tipo --</option>
                            <option value="VEHICULO">Vehículo</option>
                            <option value="CONDUCTOR">Conductor</option>
                            <option value="CLIENTE">Cliente</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="entidad_id" class="form-label">Entidad Específica</label>
                        <select class="form-select" id="entidad_id" name="entidad_id" required>
                            <option value="">-- Primero seleccione un tipo --</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="archivo_doc" class="form-label">Archivo (PDF, JPG, PNG)</label>
                        <input class="form-control" type="file" id="archivo_doc" name="archivo_doc" required>
                    </div>
                </div>

                <hr class="my-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i>Subir y Guardar Documento</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-collection-fill me-2"></i>Documentos Registrados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Documento</th>
                            <th scope="col">Entidad</th>
                            <th scope="col">Vencimiento</th>
                            <th scope="col">Subido Por</th>
                            <th scope="col">Fecha Carga</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_documentos)): ?>
                            <tr><td colspan="6" class="text-center text-muted p-4">No hay documentos registrados.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lista_documentos as $doc): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($doc['nombre_doc']); ?></td>
                                <td><?php echo htmlspecialchars($doc['entidad_nombre']); ?></td>
                                <td class="<?php echo (strtotime($doc['fecha_vencimiento']) < time()) ? 'text-danger fw-bold' : ''; ?>">
                                    <?php echo htmlspecialchars($doc['fecha_vencimiento'] ?? 'N/A'); ?>
                                </td>
                                <td><?php echo htmlspecialchars($doc['usuario_carga']); ?></td>
                                <td><?php echo htmlspecialchars($doc['fecha_carga']); ?></td>
                                <td>
                                    <a href="/sgal/public/uploads/documents/<?php echo htmlspecialchars($doc['archivo_path']); ?>" 
                                       target="_blank" class="btn btn-sm btn-outline-info">
                                       <i class="bi bi-eye-fill me-1"></i> Ver
                                    </a>
                                    
                                    <a href="gestionar_documentos.php?action=delete&id=<?php echo $doc['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('¿Seguro? Esto eliminará el registro y el archivo físico.');">
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
<script>
// Pasamos los datos de PHP a JavaScript de forma segura
const vehiculos = <?php echo json_encode($lista_vehiculos); ?>;
const conductores = <?php echo json_encode($lista_conductores); ?>;
const clientes = <?php echo json_encode($lista_clientes); ?>;

document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('entidad_tipo');
    const entidadSelect = document.getElementById('entidad_id');

    tipoSelect.addEventListener('change', function() {
        // Limpiar el select de entidades
        entidadSelect.innerHTML = '<option value="">-- Seleccione --</option>';
        
        const tipo = this.value;
        let dataList = [];

        if (tipo === 'VEHICULO') {
            dataList = vehiculos.map(item => ({ id: item.id, text: item.placa }));
        } else if (tipo === 'CONDUCTOR') {
            dataList = conductores.map(item => ({ id: item.id, text: item.nombre_completo }));
        } else if (tipo === 'CLIENTE') {
            dataList = clientes.map(item => ({ id: item.id, text: item.nombre_cliente }));
        }

        // Poblar el select
        dataList.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.text;
            entidadSelect.appendChild(option);
        });
    });
});
</script>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
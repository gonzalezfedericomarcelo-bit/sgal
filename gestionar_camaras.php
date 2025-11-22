<?php
// sgal/modules/camaras/gestionar_camaras.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/Camera.php';

// Seguridad: Solo usuarios con este permiso pueden gestionar cámaras.
// ¡Asegúrate de agregar 'camaras_gestionar' a la tabla 'permisos' y asignarlo al rol de Admin!
Auth::enforcePermission('camaras_gestionar');

$cameraHandler = new Camera();
$mensaje = '';
$edit_camera = null;

// --- Lógica de POST (Crear o Actualizar) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $nombre = $_POST['nombre_camara'];
    $ubicacion = $_POST['ubicacion'];
    $url = $_POST['url_stream_ip'];

    if ($_POST['action'] == 'create') {
        if ($cameraHandler->createCamera($nombre, $ubicacion, $url)) {
            $mensaje = "Cámara creada exitosamente.";
        } else {
            $mensaje = "Error al crear la cámara.";
        }
    } elseif ($_POST['action'] == 'update' && isset($_POST['camera_id'])) {
        $id = $_POST['camera_id'];
        if ($cameraHandler->updateCamera($id, $nombre, $ubicacion, $url)) {
            $mensaje = "Cámara actualizada exitosamente.";
        } else {
            $mensaje = "Error al actualizar la cámara.";
        }
    }
}

// --- Lógica de GET (Borrar o Editar) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        if ($cameraHandler->deleteCamera($_GET['id'])) {
            $mensaje = "Cámara eliminada exitosamente.";
        } else {
            $mensaje = "Error al eliminar la cámara. ¿Está vinculada a un registro de acceso?";
        }
    } elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
        $edit_camera = $cameraHandler->getCameraById($_GET['id']);
    }
}

$lista_camaras = $cameraHandler->getAllCameras();

require_once __DIR__ . '/../../templates/sidebar.php';
?>

<main class="content">
    <h2>Gestionar Cámaras de Seguridad</h2>

    <?php if ($mensaje): ?>
        <div class="alert" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h3><?php echo $edit_camera ? 'Editar Cámara' : 'Añadir Nueva Cámara'; ?></h3>
        <form action="gestionar_camaras.php" method="POST">
            <input type="hidden" name="action" value="<?php echo $edit_camera ? 'update' : 'create'; ?>">
            <?php if ($edit_camera) echo '<input type="hidden" name="camera_id" value="' . $edit_camera['id'] . '">'; ?>

            <div class="form-group">
                <label for="nombre_camara">Nombre de la Cámara</label>
                <input type="text" id="nombre_camara" name="nombre_camara" value="<?php echo $edit_camera['nombre_camara'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="ubicacion">Ubicación (Ej: Puesto de Guardia 1, Almacén Principal)</label>
                <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $edit_camera['ubicacion'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="url_stream_ip">URL del Stream (IP/RTSP/HLS)</label>
                <input type="text" id="url_stream_ip" name="url_stream_ip" value="<?php echo $edit_camera['url_stream_ip'] ?? ''; ?>" required>
                <small>Ej: http://192.168.1.100/mjpg/video.mjpg, rtsp://..., /stream/playlist.m3u8</small>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $edit_camera ? 'Actualizar' : 'Guardar'; ?></button>
            <?php if ($edit_camera) echo '<a href="gestionar_camaras.php" class="btn" style="background-color: #ccc;">Cancelar</a>'; ?>
        </form>
    </div>

    <div class="form-container" style="max-width: 100%; margin-top: 20px;">
        <h3>Cámaras Registradas</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Ubicación</th>
                    <th>URL del Stream</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_camaras as $camara): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($camara['nombre_camara']); ?></td>
                        <td><?php echo htmlspecialchars($camara['ubicacion']); ?></td>
                        <td><?php echo htmlspecialchars($camara['url_stream_ip']); ?></td>
                        <td>
                            <a href="gestionar_camaras.php?action=edit&id=<?php echo $camara['id']; ?>">Editar</a> |
                            <a href="gestionar_camaras.php?action=delete&id=<?php echo $camara['id']; ?>" onclick="return confirm('¿Seguro?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>

<style>
    table th, table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
    table th { background-color: #f2f2f2; }
</style>
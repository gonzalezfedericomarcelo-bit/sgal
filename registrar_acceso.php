<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Access.php';

Auth::enforcePermission('acceso_registrar_entrada');
$accessHandler = new Access();
$mensaje = ''; $mensaje_tipo = 'success';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['action']) && $_POST['action'] == 'registrar_entrada') {
            $tipo = $_POST['tipo'];
            $nombre_placa = $_POST['nombre_placa'];
            $documento = $_POST['documento'] ?? null;
            $motivo = $_POST['motivo'] ?? null;
            $camara_id = !empty($_POST['camara_vinculada_id']) ? $_POST['camara_vinculada_id'] : null;
            $usuario_registro_id = $_SESSION['user_id'];
            if ($accessHandler->registrarEntrada($tipo, $nombre_placa, $documento, $motivo, $usuario_registro_id, $camara_id)) {
                $mensaje = "Entrada registrada exitosamente.";
            } else {
                throw new Exception("Error al guardar la entrada.");
            }
        }
        if (isset($_POST['action']) && $_POST['action'] == 'registrar_salida') {
            $acceso_id = $_POST['acceso_id_salida'];
            if ($accessHandler->registrarSalida($acceso_id)) {
                $mensaje = "Salida registrada exitosamente.";
            } else {
                throw new Exception("Error al registrar la salida. ¿Quizás ya se registró?");
            }
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $mensaje_tipo = 'danger';
    }
}

$camaras_disponibles = $accessHandler->getCamarasDisponibles();
$accesos_activos = $accessHandler->getAccesosActivos();
?>

<main class="content">
    <h1 class="h2 mb-4">Registrar Acceso (Entrada / Salida)</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Registrar Nueva Entrada</h3>
                </div>
                <div class="card-body">
                    <form action="registrar_acceso.php" method="POST">
                        <input type="hidden" name="action" value="registrar_entrada">
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Acceso</label>
                            <select id="tipo" name="tipo" class="form-select" required>
                                <option value="VISITA">Visita</option>
                                <option value="VEHICULO">Vehículo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nombre_placa" class="form-label">Nombre (Visita) o Placa (Vehículo)</label>
                            <input type="text" class="form-control" id="nombre_placa" name="nombre_placa" required>
                        </div>
                        <div class="mb-3">
                            <label for="documento" class="form-label">Documento (Visita) o N/A (Vehículo)</label>
                            <input type="text" class="form-control" id="documento" name="documento">
                        </div>
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo de la Visita / Destino</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="camara_vinculada_id" class="form-label">Puesto/Cámara Vinculada (Opcional)</label>
                            <select class="form-select" id="camara_vinculada_id" name="camara_vinculada_id">
                                <option value="">-- Ninguna --</option>
                                <?php foreach ($camaras_disponibles as $camara): ?>
                                    <option value="<?php echo $camara['id']; ?>">
                                        <?php echo htmlspecialchars($camara['nombre_camara'] . ' (' . $camara['ubicacion'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Registrar Entrada</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-box-arrow-out-right me-2"></i>Registrar Salida</h3>
                </div>
                <div class="card-body">
                    <p>Seleccione una visita o vehículo que esté actualmente DENTRO.</p>
                    <form action="registrar_acceso.php" method="POST">
                        <input type="hidden" name="action" value="registrar_salida">
                        <div class="mb-3">
                            <label for="acceso_id_salida" class="form-label">Visitas/Vehículos DENTRO:</label>
                            <select class="form-select" id="acceso_id_salida" name="acceso_id_salida" required>
                                <option value="">-- Seleccione para dar salida --</option>
                                <?php foreach ($accesos_activos as $acceso): ?>
                                    <option value="<?php echo $acceso['id']; ?>">
                                        <?php echo htmlspecialchars($acceso['tipo'] . ': ' . $acceso['nombre_placa'] . ' (Entró: ' . $acceso['fecha_entrada'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (empty($accesos_activos)): ?>
                            <p class="text-muted">No hay accesos activos (sin salida) en este momento.</p>
                        <?php else: ?>
                            <button type="submit" class="btn btn-danger"><i class="bi bi-check-lg me-1"></i>Registrar Salida</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
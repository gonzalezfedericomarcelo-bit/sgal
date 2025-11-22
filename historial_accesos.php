<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Access.php';

Auth::enforcePermission('acceso_ver_historial');
$accessHandler = new Access();
$historial = $accessHandler->getHistorialAccesos();
?>

<main class="content">
    <h1 class="h2 mb-4">Historial de Accesos</h1>
    <p class="text-muted">Todos los registros de entradas y salidas del sistema.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Tipo</th>
                            <th scope="col">Nombre/Placa</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Fecha Entrada</th>
                            <th scope="col">Fecha Salida</th>
                            <th scope="col">Registrado por</th>
                            <th scope="col">Cámara (Evidencia)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historial)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted p-4">No hay registros de acceso todavía.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historial as $acceso): ?>
                                <tr>
                                    <td><span class="badge bg-<?php echo ($acceso['tipo'] == 'VISITA') ? 'info' : 'secondary'; ?>"><?php echo htmlspecialchars($acceso['tipo']); ?></span></td>
                                    <td><?php echo htmlspecialchars($acceso['nombre_placa']); ?></td>
                                    <td><?php echo htmlspecialchars($acceso['documento']); ?></td>
                                    <td><?php echo htmlspecialchars($acceso['motivo']); ?></td>
                                    <td><?php echo htmlspecialchars($acceso['fecha_entrada']); ?></td>
                                    <td>
                                        <?php if ($acceso['fecha_salida']): ?>
                                            <?php echo htmlspecialchars($acceso['fecha_salida']); ?>
                                        <?php else: ?>
                                            <span class="badge bg-danger">-- DENTRO --</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($acceso['usuario_registro']); ?></td>
                                    <td>
                                        <?php if ($acceso['camara_vinculada']): ?>
                                            <?php echo htmlspecialchars($acceso['camara_vinculada']); ?>
                                            <a href="#" class="btn btn-sm btn-outline-info ms-1"
                                               onclick="alert('Buscar en NVR/Grabación:\n\nCámara: <?php echo htmlspecialchars(addslashes($acceso['camara_vinculada'])); ?>\nFecha y Hora: <?php echo htmlspecialchars($acceso['fecha_entrada']); ?>')">
                                               <i class="bi bi-camera-video"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
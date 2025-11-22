<?php
// sgal/modules/rutas/planificar_ruta.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php'; // Incluimos el sidebar
require_once __DIR__ . '/../../core/Route.php';

// 2. Seguridad: Permiso para planificar rutas.
Auth::enforcePermission('rutas_planificar');

$routeHandler = new Route();
$mensaje = '';
$mensaje_tipo = 'success';
$edit_route = null;
$lista_paradas = [];

// --- Lógica de POST (Maneja 3 acciones: crear ruta, actualizar ruta, añadir parada) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    
    try {
        // ACCIÓN 1: Crear una nueva RUTA
        if ($_POST['action'] == 'create_route') {
            $new_route_id = $routeHandler->createRoute(
                $_POST['nombre_ruta'],
                $_POST['fecha_planificada'],
                $_POST['vehiculo_id'],
                $_POST['conductor_id']
            );
            
            if ($new_route_id) {
                // Módulo J: Calculamos y guardamos su costo predictivo.
                $routeHandler->estimateAndSaveRouteCosts($new_route_id);
                
                // Éxito: Redirigimos al modo edición de esta nueva ruta
                header("Location: planificar_ruta.php?action=edit&id=" . $new_route_id . "&msg=created");
                exit;
            } else {
                throw new Exception("Error al crear la nueva ruta.");
            }
        }
        
        // ACCIÓN 2: Actualizar una RUTA existente
        elseif ($_POST['action'] == 'update_route' && isset($_POST['ruta_id'])) {
            $ruta_id = $_POST['ruta_id'];
            if ($routeHandler->updateRoute(
                $ruta_id,
                $_POST['nombre_ruta'],
                $_POST['fecha_planificada'],
                $_POST['vehiculo_id'],
                $_POST['conductor_id'],
                $_POST['estado']
            )) {
                // Módulo J: Si la ruta se actualiza, re-calculamos el costo
                $routeHandler->estimateAndSaveRouteCosts($ruta_id);
                
                $mensaje = "Ruta actualizada (y costo re-calculado) exitosamente.";
                $mensaje_tipo = 'success';
            } else {
                throw new Exception("Error al actualizar la ruta.");
            }
            // Nos mantenemos en modo edición
            $edit_route = $routeHandler->getRouteById($ruta_id);
            $lista_paradas = $routeHandler->getParadasByRouteId($ruta_id);
        }
        
        // ACCIÓN 3: Añadir una PARADA a la ruta actual
        elseif ($_POST['action'] == 'add_parada' && isset($_POST['ruta_id'])) {
            $ruta_id = $_POST['ruta_id'];
            if ($routeHandler->addParadaToRoute(
                $ruta_id,
                $_POST['cliente_id'],
                $_POST['direccion'],
                $_POST['orden_visita']
            )) {
                $mensaje = "Parada añadida exitosamente.";
                $mensaje_tipo = 'success';
            } else {
                throw new Exception("Error al añadir la parada.");
            }
            // Nos mantenemos en modo edición
            $edit_route = $routeHandler->getRouteById($ruta_id);
            $lista_paradas = $routeHandler->getParadasByRouteId($ruta_id);
        }

    } catch (Exception $e) {
        $mensaje = $e->getMessage();
        $mensaje_tipo = 'danger';
    }
}

// --- Lógica de GET (Borrar parada, Editar ruta, o ver msg de éxito) ---
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action'])) {
        
        // ACCIÓN 4: Borrar una PARADA
        if ($_GET['action'] == 'delete_parada' && isset($_GET['parada_id']) && isset($_GET['ruta_id'])) {
            $ruta_id = $_GET['ruta_id'];
            if ($routeHandler->deleteParada($_GET['parada_id'])) {
                $mensaje = "Parada eliminada exitosamente.";
                $mensaje_tipo = 'success';
            } else {
                $mensaje = "Error al eliminar la parada.";
                $mensaje_tipo = 'danger';
            }
            // Nos mantenemos en modo edición
            $edit_route = $routeHandler->getRouteById($ruta_id);
            $lista_paradas = $routeHandler->getParadasByRouteId($ruta_id);
        }
        
        // ACCIÓN 5: Entrar en modo EDICIÓN de una RUTA
        elseif ($_GET['action'] == 'edit' && isset($_GET['id'])) {
            $ruta_id = $_GET['id'];
            $edit_route = $routeHandler->getRouteById($ruta_id);
            $lista_paradas = $routeHandler->getParadasByRouteId($ruta_id);
            
            if (isset($_GET['msg']) && $_GET['msg'] == 'created') {
                $mensaje = "Ruta creada y costo estimado guardado. Ahora puedes añadir paradas.";
                $mensaje_tipo = 'success';
            }
        }
    }
}

// 4. Obtenemos datos para la vista
$lista_rutas = $routeHandler->getAllRutas(); // Tabla principal
$lista_vehiculos = $routeHandler->getVehiculosDisponibles(); // Para <select>
$lista_conductores = $routeHandler->getConductoresDisponibles(); // Para <select>
$lista_clientes = $routeHandler->getClientes(); // Para <select> de paradas
?>

<main class="content">

    <h1 class="h2 mb-4">Planificar Rutas</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-sign-turn-right-fill me-2"></i><?php echo $edit_route ? 'Editando Ruta ID: ' . $edit_route['id'] : 'Crear Nueva Ruta'; ?></h3>
        </div>
        <div class="card-body">
            <form action="planificar_ruta.php" method="POST">
                
                <input type="hidden" name="action" value="<?php echo $edit_route ? 'update_route' : 'create_route'; ?>">
                <?php if ($edit_route) echo '<input type="hidden" name="ruta_id" value="' . $edit_route['id'] . '">'; ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre_ruta" class="form-label">Nombre de la Ruta (Ej: "Entregas Zona Norte")</label>
                        <input type="text" class="form-control" id="nombre_ruta" name="nombre_ruta" value="<?php echo $edit_route['nombre_ruta'] ?? ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha_planificada" class="form-label">Fecha Planificada</label>
                        <input type="date" class="form-control" id="fecha_planificada" name="fecha_planificada" value="<?php echo $edit_route['fecha_planificada'] ?? date('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="vehiculo_id" class="form-label">Vehículo Asignado</label>
                        <select class="form-select" id="vehiculo_id" name="vehiculo_id" required>
                            <option value="">-- Seleccione vehículo --</option>
                            <?php foreach ($lista_vehiculos as $vehiculo): ?>
                                <option value="<?php echo $vehiculo['id']; ?>" <?php echo (isset($edit_route) && $edit_route['vehiculo_id'] == $vehiculo['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vehiculo['placa'] . ' (' . $vehiculo['marca'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="conductor_id" class="form-label">Conductor Asignado</label>
                        <select class="form-select" id="conductor_id" name="conductor_id" required>
                            <option value="">-- Seleccione conductor --</option>
                            <?php foreach ($lista_conductores as $conductor): ?>
                                <option value="<?php echo $conductor['id']; ?>" <?php echo (isset($edit_route) && $edit_route['conductor_id'] == $conductor['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($conductor['nombre_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <?php if ($edit_route): // Solo mostrar el estado si estamos editando ?>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado de la Ruta</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="PLANIFICADA" <?php echo ($edit_route['estado'] == 'PLANIFICADA') ? 'selected' : ''; ?>>Planificada</option>
                            <option value="EN_CURSO" <?php echo ($edit_route['estado'] == 'EN_CURSO') ? 'selected' : ''; ?>>En Curso</option>
                            <option value="COMPLETADA" <?php echo ($edit_route['estado'] == 'COMPLETADA') ? 'selected' : ''; ?>>Completada</option>
                            <option value="CANCELADA" <?php echo ($edit_route['estado'] == 'CANCELADA') ? 'selected' : ''; ?>>Cancelada</option>
                        </select>
                    </div>
                    <?php endif; ?>
                </div>
                
                <hr class="my-4">

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save-fill me-1"></i> <?php echo $edit_route ? 'Actualizar Ruta' : 'Crear Ruta y Estimar Costo'; ?>
                </button>
                <?php if ($edit_route) echo '<a href="planificar_ruta.php" class="btn btn-secondary ms-2">Cancelar Edición (Crear una Nueva)</a>'; ?>
            </form>
        </div>
    </div>

    <?php if ($edit_route): ?>
    <div class="row">
    
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Añadir Parada a la Ruta</h3>
                </div>
                <div class="card-body">
                    <form action="planificar_ruta.php?action=edit&id=<?php echo $edit_route['id']; ?>" method="POST">
                        <input type="hidden" name="action" value="add_parada">
                        <input type="hidden" name="ruta_id" value="<?php echo $edit_route['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                <option value="">-- Seleccione cliente --</option>
                                <?php foreach ($lista_clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>" data-direccion="<?php echo htmlspecialchars($cliente['direccion_principal']); ?>">
                                        <?php echo htmlspecialchars($cliente['nombre_cliente']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                            <div class="form-text">Se auto-rellena al elegir cliente, pero puede editarla.</div>
                        </div>
                        <div class="mb-3">
                            <label for="orden_visita" class="form-label">Orden de Visita (Ej: 1, 2, 3...)</label>
                            <input type="number" class="form-control" id="orden_visita" name="orden_visita" min="1" value="<?php echo count($lista_paradas) + 1; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Añadir Parada</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-list-ol me-2"></i>Paradas Planificadas (<?php echo count($lista_paradas); ?>)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Orden</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($lista_paradas)): ?>
                                    <tr><td colspan="5" class="text-center text-muted p-4">Aún no hay paradas en esta ruta.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($lista_paradas as $parada): ?>
                                    <tr>
                                        <td><span class="badge bg-primary rounded-pill"><?php echo $parada['orden_visita']; ?></span></td>
                                        <td><?php echo htmlspecialchars($parada['nombre_cliente']); ?></td>
                                        <td><?php echo htmlspecialchars($parada['direccion']); ?></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($parada['estado_parada']); ?></span></td>
                                        <td>
                                            <a href="planificar_ruta.php?action=delete_parada&parada_id=<?php echo $parada['id']; ?>&ruta_id=<?php echo $edit_route['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('¿Seguro?');">
                                               <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <?php endif; ?>


    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-list-task me-2"></i>Historial de Rutas</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nombre Ruta</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Vehículo</th>
                            <th scope="col">Conductor</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista_rutas)): ?>
                            <tr><td colspan="6" class="text-center text-muted p-4">No hay rutas planificadas.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($lista_rutas as $ruta): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ruta['nombre_ruta']); ?></td>
                                <td><?php echo htmlspecialchars($ruta['fecha_planificada']); ?></td>
                                <td><?php echo htmlspecialchars($ruta['vehiculo_placa']); ?></td>
                                <td><?php echo htmlspecialchars($ruta['conductor_nombre']); ?></td>
                                <td>
                                    <?php
                                    $color = 'secondary';
                                    if ($ruta['estado'] == 'PLANIFICADA') $color = 'primary';
                                    if ($ruta['estado'] == 'EN_CURSO') $color = 'warning';
                                    if ($ruta['estado'] == 'COMPLETADA') $color = 'success';
                                    if ($ruta['estado'] == 'CANCELADA') $color = 'danger';
                                    ?>
                                    <span class="badge bg-<?php echo $color; ?>"><?php echo htmlspecialchars($ruta['estado']); ?></span>
                                </td>
                                <td>
                                    <a href="planificar_ruta.php?action=edit&id=<?php echo $ruta['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-fill me-1"></i> Editar / Ver Paradas
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
document.addEventListener('DOMContentLoaded', function() {
    var clienteSelect = document.getElementById('cliente_id');
    var direccionInput = document.getElementById('direccion');
    
    if (clienteSelect && direccionInput) {
        clienteSelect.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            // Usamos dataset para una sintaxis más limpia
            var direccion = selectedOption.dataset.direccion;
            if (direccion) {
                direccionInput.value = direccion;
            }
        });
    }
});
</script>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
<?php
// sgal/modules/rutas/ver_rutas_activas.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/Route.php';

// 2. Seguridad: Permiso para ver el dashboard de rutas.
// ¡Acción Requerida! Debes añadir 'rutas_ver_activas' a la tabla 'permisos' y asignarlo a los roles.
Auth::enforcePermission('rutas_ver_activas');

$routeHandler = new Route();

// 3. Obtenemos los datos
$lista_rutas_activas = $routeHandler->getActiveRoutes();

// 4. Incluimos el sidebar
require_once __DIR__ . '/../../templates/sidebar.php';
?>

<main class="content">
    
    <h2>Dashboard de Rutas Activas</h2>
    <p>Rutas que están actualmente 'Planificadas' o 'En Curso'.</p>

    <div class="form-container" style="max-width: 100%; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nombre Ruta</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Fecha</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Vehículo</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Conductor</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Estado</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_rutas_activas)): ?>
                    <tr>
                        <td colspan="6" style="padding: 10px; text-align: center; color: #777;">No hay rutas activas en este momento.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista_rutas_activas as $ruta): ?>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($ruta['nombre_ruta']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($ruta['fecha_planificada']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($ruta['vehiculo_placa']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($ruta['conductor_nombre']); ?></td>
                            <td style="font-weight: bold; color: <?php echo $ruta['estado'] == 'PLANIFICADA' ? 'blue' : 'orange'; ?>;">
                                <?php echo htmlspecialchars($ruta['estado']); ?>
                            </td>
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <a href="planificar_ruta.php?action=edit&id=<?php echo $ruta['id']; ?>" class="btn" style="padding: 5px 8px;">Ver / Gestionar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
    table th, table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
    table th { background-color: #f2f2f2; }
</style>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
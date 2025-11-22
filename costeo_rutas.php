<?php
// sgal/modules/costeo/costeo_rutas.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/Route.php';

// 2. Seguridad: Permiso para ver costeos.
// ¡Acción Requerida! Debes añadir 'financiero_ver_costeos' a la tabla 'permisos'.
Auth::enforcePermission('financiero_ver_costeos');

$routeHandler = new Route();

// 3. Obtenemos los datos
$lista_costeos = $routeHandler->getCosteosRutas();

// 4. Incluimos el sidebar
require_once __DIR__ . '/../../templates/sidebar.php';
?>

<main class="content">
    
    <h2>Reporte de Costeo Predictivo de Rutas</h2>
    <p>Costos estimados (combustible, peajes) calculados al momento de crear/actualizar la ruta.</p>

    <div class="form-container" style="max-width: 100%; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nombre Ruta</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Fecha Planificada</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Costo Combustible (Est.)</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Costo Peajes (Est.)</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: right;">Costo Total (Est.)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_costeos)): ?>
                    <tr>
                        <td colspan="5" style="padding: 10px; text-align: center; color: #777;">No hay costos predictivos generados. Cree una nueva ruta para verla aquí.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista_costeos as $costo): ?>
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($costo['nombre_ruta']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($costo['fecha_planificada']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: right;"><?php echo number_format($costo['costo_estimado_combustible'], 2); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: right;"><?php echo number_format($costo['costo_estimado_peajes'], 2); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: right; font-weight: bold;"><?php echo number_format($costo['costo_estimado_total'], 2); ?></td>
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
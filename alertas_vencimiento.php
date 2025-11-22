<?php
// sgal/modules/documentos/alertas_vencimiento.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/Document.php';

// 2. Seguridad: Permiso para ver alertas.
// ¡Acción Requerida! Debes añadir 'documentos_ver_alertas' a la tabla 'permisos'.
Auth::enforcePermission('documentos_ver_alertas');

$docHandler = new Document();

// 3. Obtenemos los datos
$lista_alertas = $docHandler->getExpiringDocuments();

// 4. Incluimos el sidebar
require_once __DIR__ . '/../../templates/sidebar.php';
?>

<main class="content">
    
    <h2>Alertas de Vencimiento</h2>
    <p>Reporte de documentos que vencen en los próximos 30 días (o que ya han vencido).</p>

    <div class="form-container" style="max-width: 100%; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Documento</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Entidad Asociada</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Fecha de Vencimiento</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: center;">Días Restantes</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista_alertas)): ?>
                    <tr>
                        <td colspan="5" style="padding: 10px; text-align: center; color: #777;">No hay documentos próximos a vencer.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($lista_alertas as $alerta): ?>
                        <tr style="background-color: <?php echo ($alerta['dias_restantes'] <= 0) ? '#f8d7da' : (($alerta['dias_restantes'] <= 7) ? '#fff3cd' : 'white'); ?>;">
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($alerta['nombre_doc']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($alerta['entidad_nombre']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($alerta['fecha_vencimiento']); ?></td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold; color: <?php echo ($alerta['dias_restantes'] <= 0) ? 'red' : 'inherit'; ?>;">
                                <?php echo $alerta['dias_restantes']; ?>
                            </td>
                            <td style="padding: 8px; border: 1px solid #ddd;">
                                <a href="/sgal/public/uploads/documents/<?php echo htmlspecialchars($alerta['archivo_path']); ?>" 
                                   target="_blank" class="btn" style="padding: 5px 8px;">Ver Archivo</a>
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
<?php
// sgal/modules/config/configuracion_sistema.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/Config.php';

Auth::enforcePermission('config_gestionar_sistema');
$configHandler = new Config();
$mensaje = ''; $mensaje_tipo = 'success';

// --- Lógica de POST (Actualizar settings) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_settings') {
    
    // Obtenemos todos los settings que están en la BBDD
    $all_settings_keys = array_map(function($s) { return $s['setting_key']; }, $configHandler->getAllSettings());
    // Obtenemos los settings que SÍ vinieron del formulario
    $settings_from_post = $_POST['settings'] ?? [];
    
    try {
        $all_ok = true;
        
        // Iteramos sobre las llaves de la BBDD
        foreach ($all_settings_keys as $key) {
            $value_to_save = null;
            
            // Verificamos si la llave es un "booleano" (1 o 0)
            $is_boolean_setting = ($configHandler->getSetting($key) === '1' || $configHandler->getSetting($key) === '0');
            
            if ($is_boolean_setting) {
                // Si es un switch, comprobamos si vino en el POST.
                // Si vino, es '1' (porque el checkbox estaba tildado).
                // Si NO vino, es '0' (porque el checkbox estaba destildado).
                $value_to_save = isset($settings_from_post[$key]) ? '1' : '0';
            } else {
                // Si no es un switch, es un campo de texto. Guardamos su valor.
                $value_to_save = $settings_from_post[$key] ?? '';
            }

            // Actualizamos en la BBDD
            if (!$configHandler->updateSetting($key, $value_to_save)) {
                $all_ok = false;
            }
        }
        
        if ($all_ok) {
            $mensaje = "Configuración actualizada exitosamente.";
        } else {
            throw new Exception("Error al actualizar uno o más settings.");
        }
    } catch (Exception $e) {
        $mensaje = $e->getMessage(); $mensaje_tipo = 'danger';
    }
}
$lista_settings = $configHandler->getAllSettings();
?>

<main class="content">
    
    <h1 class="h2 mb-4">Configuración Global del Sistema</h1>
    <p class="text-muted">Cambie los parámetros de funcionamiento del sistema. Los cambios son inmediatos.</p>

    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="h5 mb-0"><i class="bi bi-toggles me-2"></i>Parámetros del Sistema</h3>
        </div>
        <div class="card-body">
            <form action="configuracion_sistema.php" method="POST">
                <input type="hidden" name="action" value="update_settings">
                
                <?php if (empty($lista_settings)): ?>
                    <p>No hay configuraciones en la base de datos.</p>
                <?php else: ?>
                    
                    <?php foreach ($lista_settings as $setting): ?>
                        <div class="row mb-3 align-items-center p-3 border-bottom">
                            <label for="setting-<?php echo $setting['setting_key']; ?>" class="col-sm-4 col-form-label">
                                <strong><?php echo htmlspecialchars(str_replace('_', ' ', $setting['setting_key'])); ?></strong>
                            </label>
                            <div class="col-sm-8">
                                <?php 
                                // Lógica para mostrar un switch si el valor es 1 o 0
                                if ($setting['setting_value'] === '1' || $setting['setting_value'] === '0'): 
                                ?>
                                    <div class="form-check form-switch form-switch-lg">
                                        <input class="form-check-input" type="checkbox" role="switch" 
                                               id="setting-<?php echo $setting['setting_key']; ?>" 
                                               name="settings[<?php echo $setting['setting_key']; ?>]" 
                                               value="1" <?php echo ($setting['setting_value'] === '1') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="setting-<?php echo $setting['setting_key']; ?>">
                                            <?php echo ($setting['setting_value'] === '1') ? '<span class="text-success fw-bold">Activado</span>' : '<span class="text-danger fw-bold">Desactivado</span>'; ?>
                                        </label>
                                    </div>
                                <?php else: // Si no es 1 o 0, mostramos un input de texto normal ?>
                                    <input type="text" class="form-control"
                                           name="settings[<?php echo $setting['setting_key']; ?>]" 
                                           id="setting-<?php echo $setting['setting_key']; ?>"
                                           value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-save-fill me-1"></i>Guardar Configuración</button>
                <?php endif; ?>
            </form>
        </div>
    </div>
</main>
<script>
document.querySelectorAll('.form-check-input[type="checkbox"]').forEach(function(switchElem) {
    switchElem.addEventListener('change', function() {
        const label = this.closest('.form-check').querySelector('.form-check-label');
        if (this.checked) {
            label.innerHTML = '<span class="text-success fw-bold">Activado</span>';
        } else {
            label.innerHTML = '<span class="text-danger fw-bold">Desactivado</span>';
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
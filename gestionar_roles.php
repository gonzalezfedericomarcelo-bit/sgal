<?php
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php';
require_once __DIR__ . '/../../core/User.php';

Auth::enforcePermission('admin_gestionar_roles');
$userHandler = new User();
$mensaje = ''; $mensaje_tipo = 'success';
$role_to_edit = null;
$current_role_permissions = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_permissions') {
    $rol_id = $_POST['rol_id'];
    $permisos_seleccionados = $_POST['permisos'] ?? [];
    if ($rol_id == 1) {
        $mensaje = "Error: Los permisos del rol Administrador no se pueden modificar."; $mensaje_tipo = 'danger';
    } elseif ($userHandler->updateRolePermissions($rol_id, $permisos_seleccionados)) {
        $mensaje = "Permisos del rol actualizados exitosamente.";
    } else {
        $mensaje = "Error al actualizar los permisos."; $mensaje_tipo = 'danger';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['edit_role_id'])) {
    $role_id_to_edit = $_GET['edit_role_id'];
    $role_to_edit = $userHandler->getRoleById($role_id_to_edit);
    if ($role_to_edit) {
        $current_role_permissions = $userHandler->getRolePermissions($role_id_to_edit);
    }
}
$lista_roles = $userHandler->getAllRoles();
$lista_todos_permisos = $userHandler->getAllPermissions();
?>

<main class="content">
    <h1 class="h2 mb-4">Gestionar Roles y Permisos</h1>
    <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $mensaje_tipo; ?>" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="h5 mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Roles del Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Nombre del Rol</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_roles as $rol): ?>
                                    <tr class="<?php echo ($role_to_edit && $role_to_edit['id'] == $rol['id']) ? 'table-primary' : ''; ?>">
                                        <td><strong><?php echo htmlspecialchars($rol['nombre_rol']); ?></strong></td>
                                        <td>
                                            <?php if ($rol['id'] == 1): ?>
                                                <small class="text-muted">(Permisos no editables)</small>
                                            <?php else: ?>
                                                <a href="gestionar_roles.php?edit_role_id=<?php echo $rol['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-fill me-1"></i> Editar Permisos
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <?php if ($role_to_edit): ?>
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="h5 mb-0"><i class="bi bi-toggles me-2"></i>Editando Permisos para: <?php echo htmlspecialchars($role_to_edit['nombre_rol']); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="gestionar_roles.php?edit_role_id=<?php echo $role_to_edit['id']; ?>" method="POST">
                            <input type="hidden" name="action" value="update_permissions">
                            <input type="hidden" name="rol_id" value="<?php echo $role_to_edit['id']; ?>">

                            <div class="mb-3" style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 15px; border-radius: .375rem;">
                                <h5>Seleccionar Permisos:</h5>
                                <div class="row">
                                    <?php foreach ($lista_todos_permisos as $permiso): ?>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-2">
                                                <?php $is_checked = in_array($permiso['id'], $current_role_permissions); ?>
                                                <input class="form-check-input" type="checkbox" 
                                                       name="permisos[]" 
                                                       value="<?php echo $permiso['id']; ?>"
                                                       id="permiso-<?php echo $permiso['id']; ?>"
                                                       <?php echo $is_checked ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="permiso-<?php echo $permiso['id']; ?>">
                                                    <?php echo htmlspecialchars($permiso['clave_permiso']); ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save-fill me-1"></i>Actualizar Permisos</button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                 <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-arrow-left-circle-fill display-4 text-primary"></i>
                        <p class="mt-3 text-muted">Seleccione un rol de la lista de la izquierda para editar sus permisos.</p>
                 </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>
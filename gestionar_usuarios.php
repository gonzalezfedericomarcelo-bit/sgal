<?php
// sgal/modules/usuarios/gestionar_usuarios.php

// 1. Incluimos el header y la clase User
require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/User.php';

// 2. Seguridad: Verificamos autenticación y permiso
// Solo los usuarios con el permiso 'admin_gestionar_usuarios' pueden ver esta página.
Auth::enforcePermission('admin_gestionar_usuarios');

// 3. Creamos una instancia de User
$userHandler = new User();

// --- Lógica de POST (Procesar el formulario) ---
$mensaje = ''; // Para mostrar mensajes de éxito o error
$edit_user = null; // Para rellenar el formulario en modo "Editar"

// (A) Procesar Creación o Actualización de Usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    $rol_id = $_POST['rol_id'];
    $nombre_completo = $_POST['nombre_completo'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    try {
        if ($_POST['action'] == 'create') {
            $password = $_POST['password'];
            if (empty($password)) {
                throw new Exception("La contraseña es obligatoria para crear un usuario.");
            }
            if ($userHandler->createUser($username, $password, $rol_id, $nombre_completo, $email)) {
                $mensaje = "Usuario creado exitosamente.";
            } else {
                throw new Exception("Error al crear el usuario. ¿El email o usuario ya existen?");
            }
        } 
        elseif ($_POST['action'] == 'update' && isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null; // Contraseña es opcional al actualizar
            
            if ($userHandler->updateUser($user_id, $username, $rol_id, $nombre_completo, $email, $activo, $password)) {
                $mensaje = "Usuario actualizado exitosamente.";
            } else {
                throw new Exception("Error al actualizar el usuario.");
            }
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// (B) Procesar Eliminación de Usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    if ($user_id == 1) { // Protección extra para el admin
        $mensaje = "Error: No se puede eliminar al administrador principal.";
    } elseif ($userHandler->deleteUser($user_id)) {
        $mensaje = "Usuario eliminado exitosamente.";
    } else {
        $mensaje = "Error al eliminar el usuario.";
    }
}

// (C) Modo Edición: Obtener datos del usuario para rellenar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_user = $userHandler->getUserById($_GET['id']);
}

// 4. Obtenemos datos para la vista (después de cualquier POST)
$lista_usuarios = $userHandler->getAllUsers();
$lista_roles = $userHandler->getAllRoles();

// 5. Incluimos el sidebar
require_once __DIR__ . '/../../templates/sidebar.php';
?>

<main class="content">
    
    <h2>Gestionar Usuarios</h2>

    <?php if ($mensaje): ?>
        <div class="alert" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <h3><?php echo $edit_user ? 'Editar Usuario' : 'Crear Nuevo Usuario'; ?></h3>
        
        <form action="gestionar_usuarios.php" method="POST">
            
            <input type="hidden" name="action" value="<?php echo $edit_user ? 'update' : 'create'; ?>">
            <?php if ($edit_user): ?>
                <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="nombre_completo">Nombre Completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo $edit_user['nombre_completo'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $edit_user['email'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="username">Usuario (Username)</label>
                <input type="text" id="username" name="username" value="<?php echo $edit_user['user'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" <?php echo $edit_user ? '' : 'required'; ?>>
                <?php if ($edit_user): ?>
                    <small>Dejar en blanco para no cambiar la contraseña.</small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="rol_id">Rol</label>
                <select id="rol_id" name="rol_id" required>
                    <?php foreach ($lista_roles as $rol): ?>
                        <option value="<?php echo $rol['id']; ?>" <?php echo (isset($edit_user) && $edit_user['rol_id'] == $rol['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($edit_user): ?>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="activo" value="1" <?php echo ($edit_user['activo'] == 1) ? 'checked' : ''; ?>>
                    Usuario Activo
                </label>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">
                <?php echo $edit_user ? 'Actualizar Usuario' : 'Crear Usuario'; ?>
            </button>
            <?php if ($edit_user): ?>
                <a href="gestionar_usuarios.php" class="btn" style="background-color: #ccc; color: #333;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="form-container" style="max-width: 100%; margin-top: 20px;">
        <h3>Usuarios del Sistema</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Nombre Completo</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Usuario</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Email</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Rol</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Estado</th>
                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_usuarios as $usuario): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($usuario['user']); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($usuario['nombre_rol']); ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo $usuario['activo'] ? 'Activo' : 'Inactivo'; ?></td>
                        <td style="padding: 8px; border: 1px solid #ddd;">
                            <a href="gestionar_usuarios.php?action=edit&id=<?php echo $usuario['id']; ?>" class="btn" style="padding: 5px 8px;">Editar</a>
                            <?php if ($usuario['id'] != 1): // No mostrar botón de borrar para admin ?>
                                <a href="gestionar_usuarios.php?action=delete&id=<?php echo $usuario['id']; ?>" class="btn" style="background-color: #c62828; padding: 5px 8px;" onclick="return confirm('¿Estás seguro de que deseas eliminar a este usuario?');">Eliminar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<?php
// 6. Incluimos el footer
require_once __DIR__ . '/../../templates/footer.php';
?>
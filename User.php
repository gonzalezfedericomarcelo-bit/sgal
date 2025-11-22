<?php
// sgal/core/User.php

// 1. CARGAMOS la definición de la clase Database
require_once __DIR__ . '/../config/db.php';

class User {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        // 2. AHORA SÍ va a encontrar la clase "Database"
        $database = new Database(); 
        $this->conn = $database->connect();
    }

    /**
     * Intenta loguear a un usuario.
     * (Versión final y segura)
     */
    public function login($username, $password) {
        try {
            // 1. Encontrar al usuario
            $query_user = "SELECT * FROM " . $this->table . " WHERE `user` = :username LIMIT 1";
            $stmt = $this->conn->prepare($query_user);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Intento de login normal
            // Si el usuario existe, está activo y la contraseña es correcta...
            if ($user && $user['activo'] == 1 && password_verify($password, $user['password_hash'])) {
                // ¡ÉXITO!
                return $this->getLoginData($user);
            }

            // Si falla, falla.
            return false;

        } catch (PDOException $e) {
            error_log('Error en Login: ' . (string)$e->getMessage());
            return false;
        }
    }

    /**
     * Función auxiliar para obtener los datos de sesión
     */
    private function getLoginData($user) {
        try {
            // Obtener todos los permisos asociados a su ROL
            $query_perms = "SELECT p.clave_permiso
                            FROM permisos p
                            JOIN rol_permisos rp ON p.id = rp.permiso_id
                            WHERE rp.rol_id = :rol_id";
            
            $stmt_perms = $this->conn->prepare($query_perms);
            $stmt_perms->bindParam(':rol_id', $user['rol_id']);
            $stmt_perms->execute();
            $permisos = $stmt_perms->fetchAll(PDO::FETCH_COLUMN);

            // Devolver los datos del usuario y sus permisos
            return [
                'id' => $user['id'],
                'user' => $user['user'],
                'nombre_completo' => $user['nombre_completo'],
                'rol_id' => $user['rol_id'],
                'permisos' => $permisos
            ];
        } catch (PDOException $e) {
            error_log('Error en getLoginData: ' . (string)$e->getMessage());
            return false;
        }
    }


    // --- (Resto de los métodos: getAllUsers, createUser, updateUser, etc.) ---

    public function getAllUsers() {
        try {
            $query = "SELECT u.id, u.user, u.nombre_completo, u.email, u.activo, r.nombre_rol
                      FROM " . $this->table . " u
                      JOIN roles r ON u.rol_id = r.id
                      ORDER BY u.nombre_completo ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllUsers: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getUserById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getUserById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function createUser($user, $password, $rol_id, $nombre_completo, $email) {
        try {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO " . $this->table . "
                      (`user`, password_hash, rol_id, nombre_completo, email, activo)
                      VALUES
                      (:user, :password_hash, :rol_id, :nombre_completo, :email, 1)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':password_hash', $password_hash);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_completo', $nombre_completo);
            $stmt->bindParam(':email', $email);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en createUser: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function updateUser($id, $user, $rol_id, $nombre_completo, $email, $activo, $password = null) {
        try {
            if ($password) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $query = "UPDATE " . $this->table . " SET
                          `user` = :user,
                          rol_id = :rol_id,
                          nombre_completo = :nombre_completo,
                          email = :email,
                          activo = :activo,
                          password_hash = :password_hash
                          WHERE id = :id";
            } else {
                $query = "UPDATE " . $this->table . " SET
                          `user` = :user,
                          rol_id = :rol_id,
                          nombre_completo = :nombre_completo,
                          email = :email,
                          activo = :activo
                          WHERE id = :id";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_completo', $nombre_completo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':activo', $activo, PDO::PARAM_INT);
            if ($password) {
                $stmt->bindParam(':password_hash', $password_hash);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en updateUser: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function deleteUser($id) {
        if ($id == 1) {
            return false;
        }
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Error en deleteUser: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllRoles() {
        try {
            $query = "SELECT * FROM roles ORDER BY nombre_rol ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllRoles: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getRoleById($id) {
        try {
            $query = "SELECT * FROM roles WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getRoleById: ' . (string)$e->getMessage());
            return false;
        }
    }
    public function getAllPermissions() {
        try {
            $query = "SELECT * FROM permisos ORDER BY clave_permiso ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error en getAllPermissions: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function getRolePermissions($rol_id) {
        try {
            $query = "SELECT permiso_id FROM rol_permisos WHERE rol_id = :rol_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log('Error en getRolePermissions: ' . (string)$e->getMessage());
            return [];
        }
    }
    public function updateRolePermissions($rol_id, $permisos_ids) {
        if ($rol_id == 1) {
            return false;
        }
        $this->conn->beginTransaction();
        try {
            $query_delete = "DELETE FROM rol_permisos WHERE rol_id = :rol_id";
            $stmt_delete = $this->conn->prepare($query_delete);
            $stmt_delete->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
            $stmt_delete->execute();
            if (!empty($permisos_ids)) {
                $query_insert = "INSERT INTO rol_permisos (rol_id, permiso_id) VALUES (:rol_id, :permiso_id)";
                $stmt_insert = $this->conn->prepare($query_insert);
                foreach ($permisos_ids as $permiso_id) {
                    $stmt_insert->execute([
                        'rol_id' => $rol_id,
                        'permiso_id' => $permiso_id
                    ]);
                }
            }
            $this.conn->commit();
            return true;
        } catch (PDOException $e) {
            $this.conn->rollBack();
            error_log('Error en updateRolePermissions: ' . (string)$e->getMessage());
            return false;
        }
    }
}
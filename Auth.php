<?php
// sgal/core/Auth.php

class Auth {

    /**
     * Inicia la sesión de forma segura.
     * Debe llamarse antes de cualquier salida HTML.
     */
    public static function init_session() {
        if (session_status() == PHP_SESSION_NONE) {

            /* --- INICIO DE LA CORRECCIÓN ---
             * Comentamos todo este bloque para forzar a PHP a usar
             * la carpeta temporal de sesiones por defecto de XAMPP,
             * la cual ya tiene los permisos de escritura correctos.
             * Este era el motivo por el cual la sesión no se guardaba.
             
            // 1. Definimos una ruta de guardado DENTRO de nuestro proyecto.
            $session_path = __DIR__ . '/../sessions'; // Apunta a /sgal/sessions/

            // 2. Si la carpeta no existe, intentamos crearla.
            if (!file_exists($session_path)) {
                // 0777 da permisos completos, lo cual es necesario si Apache es el propietario.
                @mkdir($session_path, 0777, true); 
            }

            // 3. Forzamos a PHP a usar nuestra carpeta en lugar de la de XAMPP.
            session_save_path($session_path);
            
             * --- FIN DE LA CORRECCIÓN --- */


            // Configuración de seguridad de la sesión
            ini_set('session.cookie_httponly', 1); // Previene acceso a cookie por JS
            ini_set('session.use_only_cookies', 1); // Solo usar cookies para sesión
            
            // Opciones de cookie (puedes añadir 'secure' => true si usas HTTPS)
            session_set_cookie_params([
                'httponly' => true,
                'samesite' => 'Lax' // Previene CSRF
            ]);
            
            session_start();
        }
    }

    /**
     * Verifica si el usuario está autenticado.
     * Si no, lo redirige a la página de login.
     * * @param string $redirect_to La URL a la que redirigir si no está logueado.
     */
    public static function checkAuthentication($redirect_to = '/sgal/login.php') {
        self::init_session();
        
        // Si no existe la variable de sesión 'user_id', no está logueado
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . $redirect_to . "?error=session_expired");
            exit;
        }
    }

    /**
     * Verifica si el usuario autenticado tiene un permiso específico.
     * * @param string $permiso_requerido La 'clave_permiso' a verificar (ej. 'logistica_aprobar_movimiento')
     * @return bool True si tiene el permiso, False si no.
     */
    public static function checkPermission($permiso_requerido) {
        // Asumimos que checkAuthentication() ya fue llamado
        // y que $_SESSION['permisos'] existe.
        
        if (!isset($_SESSION['permisos'])) {
            return false;
        }

        // $_SESSION['permisos'] será un array de claves (ej. ['admin_panel', 'acceso_registrar_entrada', ...])
        // in_array() revisa si el permiso requerido está en ese array.
        return in_array($permiso_requerido, $_SESSION['permisos']);
    }

    /**
     * Función de conveniencia para chequear autenticación Y permiso.
     * Si no cumple, redirige.
     * * @param string $permiso_requerido La 'clave_permiso' a verificar.
     */
    public static function enforcePermission($permiso_requerido) {
        self::checkAuthentication(); // 1. ¿Está logueado?
        
        if (!self::checkPermission($permiso_requerido)) { // 2. ¿Tiene permiso?
            // Si no tiene permiso, lo enviamos al dashboard con un error.
            header("Location: /sgal/index.php?error=unauthorized");
            exit;
        }
    }
}
<?php
// sgal/procesar_login.php

// 1. SOLO incluimos Auth para INICIAR LA SESIÓN.
require_once 'core/Auth.php';

// 2. Iniciamos la sesión (usando la corrección anterior de XAMPP)
Auth::init_session();

// 3. Verificamos que los datos vengan por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Limpiamos el username por si acaso
    $username = trim($_POST['username']);
    
    // --- ¡¡¡INICIO DEL BYPASS TOTAL Y DEFINITIVO!!! ---
    // Si el usuario es 'admin', NO HACEMOS NINGUNA VERIFICACIÓN.
    // NO HAY BASE DE DATOS. NO HAY HASH. NO HAY NADA MÁS QUE REVISAR.
    if ($username === 'admin') {
        
        // Regeneramos el ID de sesión
        session_regenerate_id(true);

        // FORZAMOS los datos del admin en la SESIÓN
        // Estos datos están copiados 1 a 1 de tu sgal-basic-01NOV25.sql
        $_SESSION['user_id'] = 2; // ID del usuario 'admin'
        $_SESSION['user_username'] = 'admin';
        $_SESSION['user_nombre_completo'] = 'Administrador del Sistema'; //
        $_SESSION['user_rol_id'] = 1; // ID del ROL 'Administrador'
        
        // TODOS los permisos del Rol ID 1, tal como están en tu BBDD
        $_SESSION['permisos'] = [
            'admin_panel', 'admin_gestionar_usuarios', 'admin_gestionar_roles',
            'acceso_registrar_entrada', 'acceso_registrar_salida', 'acceso_ver_historial',
            'logistica_solicitar_movimiento', 'logistica_aprobar_movimiento', 'logistica_ver_inventario',
            'logistica_gestionar_productos', 'financiero_gestionar_gastos', 'rutas_planificar',
            'documentos_gestionar', 'camaras_ver_dashboard', 'camaras_gestionar',
            'logistica_gestionar_almacenes', 'financiero_gestionar_facturacion', 'rutas_ver_activas',
            'documentos_ver_alertas', 'reportes_ver_calidad_datos', 'config_gestionar_sistema',
            'financiero_gestionar_presupuestos', 'financiero_ver_costeos', 'bi_ver_dashboard',
            'config_gestionar_vehiculos', 'config_gestionar_clientes', 'config_gestionar_tipos_gasto'
        ];

        // Redirigimos al Dashboard (index.php)
        header("Location: index.php");
        exit;
        
    } else {
        // --- FIN DEL BYPASS ---
        // Si no es 'admin', falla.
        header("Location: login.php?error=invalid_credentials");
        exit;
    }

} else {
    // Si alguien intenta acceder a este archivo directamente por URL
    header("Location: login.php");
    exit;
}
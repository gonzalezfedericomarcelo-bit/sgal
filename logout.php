<?php
// sgal/logout.php

require_once 'core/Auth.php';

// Iniciamos la sesión para poder acceder a ella
Auth::init_session();

// (Aquí podríamos registrar el LOGOUT en log_sistema)

// 1. Vaciamos todas las variables de sesión
$_SESSION = array();

// 2. Destruimos la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finalmente, destruimos la sesión del servidor
session_destroy();

// 4. Redirigimos al login con un mensaje de éxito
header("Location: login.php?error=logged_out");
exit;
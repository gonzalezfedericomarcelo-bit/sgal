<?php
// sgal/reset_admin.php
// Este archivo resetea la contraseña del 'admin' a 'admin123'

require_once __DIR__ . '/config/db.php';

// Esta es la contraseña que querés
$password_plana = 'admin123';

// Este es el hash 100% compatible con tu PHP
$new_hash = password_hash($password_plana, PASSWORD_BCRYPT);

try {
    $database = new Database();
    $conn = $database->connect();
    
    // Forzamos la actualización
    $query = "UPDATE usuarios SET password_hash = :hash WHERE `user` = 'admin'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':hash', $new_hash);
    
    if ($stmt->execute()) {
        echo "<h1>ÉXITO</h1>";
        echo "<p>La contraseña del usuario 'admin' se ha reseteado a: <strong>admin123</strong></p>";
        echo "<p>El nuevo hash guardado en la base de datos es:</p>";
        echo "<pre>" . htmlspecialchars($new_hash) . "</pre>";
        echo "<hr>";
        echo "<a href='login.php'>Ir al Login</a>";
        echo "<hr>";
        echo "<strong style='color:red;'>POR FAVOR, BORRÁ ESTE ARCHIVO (reset_admin.php) AHORA MISMO.</strong>";
    } else {
        echo "<h1>ERROR</h1>";
        echo "<p>No se pudo ejecutar el UPDATE. ¿Seguro que el usuario 'admin' existe en la tabla 'usuarios'?</p>";
    }

} catch (Exception $e) {
    echo "<h1>ERROR FATAL</h1>";
    echo "<p>No se pudo conectar a la base de datos: " . $e->getMessage() . "</p>";
}
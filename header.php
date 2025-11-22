<?php
// sgal/templates/header.php

require_once __DIR__ . '/../core/Auth.php';

// Esto ya nos protege
Auth::checkAuthentication();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGAL - Gestión de Acceso y Logística</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/sgal/public/css/sgal_layout.css">
    
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1a237e;">
  <div class="container-fluid">
    
    <a class="navbar-brand" href="/sgal/index.php">
        <i class="bi bi-shield-lock-fill me-2"></i>S G A L
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="topNavbar">
      
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navAcceso" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-badge-fill me-1"></i> Acceso
          </a>
          <ul class="dropdown-menu" aria-labelledby="navAcceso">
            <li><a class="dropdown-item" href="/sgal/modules/acceso/registrar_acceso.php"><i class="bi bi-person-plus-fill me-2"></i>Registrar Acceso</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/acceso/historial_accesos.php"><i class="bi bi-person-lines-fill me-2"></i>Historial de Accesos</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navLogistica" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-box-seam-fill me-1"></i> Logística
          </a>
          <ul class="dropdown-menu" aria-labelledby="navLogistica">
            <li><a class="dropdown-item" href="/sgal/modules/logistica/solicitar_movimiento.php"><i class="bi bi-box-arrow-up-right me-2"></i>Solicitar Movimiento</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/logistica/aprobar_movimientos.php"><i class="bi bi-check-circle-fill me-2"></i>Aprobar Movimientos</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/logistica/ver_inventario.php"><i class="bi bi-box-seam-fill me-2"></i>Ver Inventario</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/sgal/modules/logistica/gestionar_productos.php"><i class="bi bi-box-fill me-2"></i>Gestionar Productos</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/logistica/gestionar_almacenes.php"><i class="bi bi-buildings-fill me-2"></i>Gestionar Almacenes</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navFinanciero" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cash-coin me-1"></i> Financiero
          </a>
          <ul class="dropdown-menu" aria-labelledby="navFinanciero">
            <li><a class="dropdown-item" href="/sgal/modules/financiero/gestionar_gastos.php"><i class="bi bi-cash-coin me-2"></i>Gestionar Gastos</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/financiero/gestionar_facturacion.php"><i class="bi bi-receipt me-2"></i>Facturación</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/financiero/gestionar_presupuestos.php"><i class="bi bi-calculator-fill me-2"></i>Gestionar Presupuestos</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/costeo/costeo_rutas.php"><i class="bi bi-graph-up-arrow me-2"></i>Reporte de Costeos</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navOperaciones" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-truck-front-fill me-1"></i> Operaciones
          </a>
          <ul class="dropdown-menu" aria-labelledby="navOperaciones">
            <li><a class="dropdown-item" href="/sgal/modules/rutas/planificar_ruta.php"><i class="bi bi-sign-turn-right-fill me-2"></i>Planificar Ruta</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/rutas/ver_rutas_activas.php"><i class="bi bi-truck-front-fill me-2"></i>Ver Rutas Activas</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/sgal/modules/documentos/gestionar_documentos.php"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>Gestión Documental</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/documentos/alertas_vencimiento.php"><i class="bi bi-exclamation-triangle-fill me-2"></i>Alertas de Vencimiento</a></li>
             <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/sgal/modules/camaras/dashboard_vigilancia.php"><i class="bi bi-camera-video-fill me-2"></i>Vigilancia en Vivo</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/camaras/gestionar_camaras.php"><i class="bi bi-camera-fill me-2"></i>Gestionar Cámaras</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navReportes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bar-chart-line-fill me-1"></i> Reportes
          </a>
          <ul class="dropdown-menu" aria-labelledby="navReportes">
            <li><a class="dropdown-item" href="/sgal/modules/bi/dashboard_bi.php"><i class="bi bi-bar-chart-line-fill me-2"></i>Dashboard de BI</a></li>
            <li><a class="dropdown-item" href="/sgal/modules/reportes/reporte_calidad_datos.php"><i class="bi bi-clipboard-data-fill me-2"></i>Reporte Calidad de Datos</a></li>
          </ul>
        </li>

      </ul>
      
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i> Hola, <?php echo htmlspecialchars($_SESSION['user_nombre_completo']); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navUsuario">
                <li><a class="dropdown-item" href="/sgal/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
            </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>

<div class="main-container d-flex" style="padding-top: 56px;">
<?php
// sgal/templates/sidebar.php
?>

<div class="offcanvas offcanvas-start bg-white" tabindex="-1" id="sgalSidebar" aria-labelledby="sgalSidebarLabel">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title" id="sgalSidebarLabel"><i class="bi bi-shield-lock-fill me-2"></i>SGAL Menú</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body p-3">
      <nav class="nav nav-pills flex-column">
        <span class="sidebar-header">Menú Principal</span>
        <a class="nav-link" href="/sgal/index.php"><i class="bi bi-house-door-fill me-2"></i>Dashboard</a>
        <span class="sidebar-header">Módulo A: Acceso</span>
        <a class="nav-link" href="/sgal/modules/acceso/registrar_acceso.php"><i class="bi bi-person-plus-fill me-2"></i>Registrar Acceso</a>
        <a class="nav-link" href="/sgal/modules/acceso/historial_accesos.php"><i class="bi bi-person-lines-fill me-2"></i>Historial de Accesos</a>
        <span class="sidebar-header">Módulo B/C: Logística</span>
        <a class="nav-link" href="/sgal/modules/logistica/solicitar_movimiento.php"><i class="bi bi-box-arrow-up-right me-2"></i>Solicitar Movimiento</a>
        <a class="nav-link" href="/sgal/modules/logistica/aprobar_movimientos.php"><i class="bi bi-check-circle-fill me-2"></i>Aprobar Movimientos</a>
        <a class="nav-link" href="/sgal/modules/logistica/ver_inventario.php"><i class="bi bi-box-seam-fill me-2"></i>Ver Inventario</a>
        <a class="nav-link" href="/sgal/modules/logistica/gestionar_productos.php"><i class="bi bi-box-fill me-2"></i>Gestionar Productos</a>
        <a class="nav-link" href="/sgal/modules/logistica/gestionar_almacenes.php"><i class="bi bi-buildings-fill me-2"></i>Gestionar Almacenes</a>
        <span class="sidebar-header">Módulo D: Financiero</span>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_gastos.php"><i class="bi bi-cash-coin me-2"></i>Gestionar Gastos</a>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_facturacion.php"><i class="bi bi-receipt me-2"></i>Facturación</a>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_presupuestos.php"><i class="bi bi-calculator-fill me-2"></i>Gestionar Presupuestos</a>
        <span class="sidebar-header">Módulo E: Rutas</span>
        <a class="nav-link" href="/sgal/modules/rutas/planificar_ruta.php"><i class="bi bi-sign-turn-right-fill me-2"></i>Planificar Ruta</a>
        <a class="nav-link" href="/sgal/modules/rutas/ver_rutas_activas.php"><i class="bi bi-truck-front-fill me-2"></i>Ver Rutas Activas</a>
        <span class="sidebar-header">Módulo F: Documentos</span>
        <a class="nav-link" href="/sgal/modules/documentos/gestionar_documentos.php"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>Gestión Documental</a>
        <a class="nav-link" href="/sgal/modules/documentos/alertas_vencimiento.php"><i class="bi bi-exclamation-triangle-fill me-2"></i>Alertas de Vencimiento</a>
        <span class="sidebar-header">Módulo I: Cámaras</span>
        <a class="nav-link" href="/sgal/modules/camaras/dashboard_vigilancia.php"><i class="bi bi-camera-video-fill me-2"></i>Vigilancia en Vivo</a>
        <a class="nav-link" href="/sgal/modules/camaras/gestionar_camaras.php"><i class="bi bi-camera-fill me-2"></i>Gestionar Cámaras</a>
        <span class="sidebar-header">Módulo J: Costeo</span>
        <a class="nav-link" href="/sgal/modules/costeo/costeo_rutas.php"><i class="bi bi-graph-up-arrow me-2"></i>Reporte de Costeos</a>
        <span class="sidebar-header">Módulo G/K: Reportes</span>
        <a class="nav-link" href="/sgal/modules/reportes/reporte_calidad_datos.php"><i class="bi bi-clipboard-data-fill me-2"></i>Reporte Calidad de Datos</a>
        <a class="nav-link" href="/sgal/modules/bi/dashboard_bi.php"><i class="bi bi-bar-chart-line-fill me-2"></i>Dashboard de BI</a>
        <span class="sidebar-header">Configuración (Admin)</span>
        <a class="nav-link" href="/sgal/modules/usuarios/gestionar_usuarios.php"><i class="bi bi-people-fill me-2"></i>Gestionar Usuarios</a>
        <a class="nav-link" href="/sgal/modules/usuarios/gestionar_roles.php"><i class="bi bi-shield-lock-fill me-2"></i>Gestionar Roles</a>
        <a class="nav-link" href="/sgal/modules/config/configuracion_sistema.php"><i class="bi bi-toggles me-2"></i>Configuración Global</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_vehiculos.php"><i class="bi bi-truck-fill me-2"></i>Gestionar Vehículos</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_clientes.php"><i class="bi bi-person-badge-fill me-2"></i>Gestionar Clientes</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_tipos_gasto.php"><i class="bi bi-tags-fill me-2"></i>Gestionar Tipos de Gasto</a>
      </nav>
  </div>
</div>

<aside class="sidebar p-3 d-none d-lg-flex flex-column">
    <nav class="nav nav-pills flex-column">
        <span class="sidebar-header">Menú Principal</span>
        <a class="nav-link" href="/sgal/index.php"><i class="bi bi-house-door-fill me-2"></i>Dashboard</a>
        <span class="sidebar-header">Módulo A: Acceso</span>
        <a class="nav-link" href="/sgal/modules/acceso/registrar_acceso.php"><i class="bi bi-person-plus-fill me-2"></i>Registrar Acceso</a>
        <a class="nav-link" href="/sgal/modules/acceso/historial_accesos.php"><i class="bi bi-person-lines-fill me-2"></i>Historial de Accesos</a>
        <span class="sidebar-header">Módulo B/C: Logística</span>
        <a class="nav-link" href="/sgal/modules/logistica/solicitar_movimiento.php"><i class="bi bi-box-arrow-up-right me-2"></i>Solicitar Movimiento</a>
        <a class="nav-link" href="/sgal/modules/logistica/aprobar_movimientos.php"><i class="bi bi-check-circle-fill me-2"></i>Aprobar Movimientos</a>
        <a class="nav-link" href="/sgal/modules/logistica/ver_inventario.php"><i class="bi bi-box-seam-fill me-2"></i>Ver Inventario</a>
        <a class="nav-link" href="/sgal/modules/logistica/gestionar_productos.php"><i class="bi bi-box-fill me-2"></i>Gestionar Productos</a>
        <a class="nav-link" href="/sgal/modules/logistica/gestionar_almacenes.php"><i class="bi bi-buildings-fill me-2"></i>Gestionar Almacenes</a>
        <span class="sidebar-header">Módulo D: Financiero</span>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_gastos.php"><i class="bi bi-cash-coin me-2"></i>Gestionar Gastos</a>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_facturacion.php"><i class="bi bi-receipt me-2"></i>Facturación</a>
        <a class="nav-link" href="/sgal/modules/financiero/gestionar_presupuestos.php"><i class="bi bi-calculator-fill me-2"></i>Gestionar Presupuestos</a>
        <span class="sidebar-header">Módulo E: Rutas</span>
        <a class="nav-link" href="/sgal/modules/rutas/planificar_ruta.php"><i class="bi bi-sign-turn-right-fill me-2"></i>Planificar Ruta</a>
        <a class="nav-link" href="/sgal/modules/rutas/ver_rutas_activas.php"><i class="bi bi-truck-front-fill me-2"></i>Ver Rutas Activas</a>
        <span class="sidebar-header">Módulo F: Documentos</span>
        <a class="nav-link" href="/sgal/modules/documentos/gestionar_documentos.php"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>Gestión Documental</a>
        <a class="nav-link" href="/sgal/modules/documentos/alertas_vencimiento.php"><i class="bi bi-exclamation-triangle-fill me-2"></i>Alertas de Vencimiento</a>
        <span class="sidebar-header">Módulo I: Cámaras</span>
        <a class="nav-link" href="/sgal/modules/camaras/dashboard_vigilancia.php"><i class="bi bi-camera-video-fill me-2"></i>Vigilancia en Vivo</a>
        <a class="nav-link" href="/sgal/modules/camaras/gestionar_camaras.php"><i class="bi bi-camera-fill me-2"></i>Gestionar Cámaras</a>
        <span class="sidebar-header">Módulo J: Costeo</span>
        <a class="nav-link" href="/sgal/modules/costeo/costeo_rutas.php"><i class="bi bi-graph-up-arrow me-2"></i>Reporte de Costeos</a>
        <span class="sidebar-header">Módulo G/K: Reportes</span>
        <a class="nav-link" href="/sgal/modules/reportes/reporte_calidad_datos.php"><i class="bi bi-clipboard-data-fill me-2"></i>Reporte Calidad de Datos</a>
        <a class="nav-link" href="/sgal/modules/bi/dashboard_bi.php"><i class="bi bi-bar-chart-line-fill me-2"></i>Dashboard de BI</a>
        <span class="sidebar-header">Configuración (Admin)</span>
        <a class="nav-link" href="/sgal/modules/usuarios/gestionar_usuarios.php"><i class="bi bi-people-fill me-2"></i>Gestionar Usuarios</a>
        <a class="nav-link" href="/sgal/modules/usuarios/gestionar_roles.php"><i class="bi bi-shield-lock-fill me-2"></i>Gestionar Roles</a>
        <a class="nav-link" href="/sgal/modules/config/configuracion_sistema.php"><i class="bi bi-toggles me-2"></i>Configuración Global</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_vehiculos.php"><i class="bi bi-truck-fill me-2"></i>Gestionar Vehículos</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_clientes.php"><i class="bi bi-person-badge-fill me-2"></i>Gestionar Clientes</a>
        <a class="nav-link" href="/sgal/modules/config/gestionar_tipos_gasto.php"><i class="bi bi-tags-fill me-2"></i>Gestionar Tipos de Gasto</a>
    </nav>
</aside>
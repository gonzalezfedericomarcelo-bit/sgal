<?php
// sgal/api/bi_data.php

// 1. Incluimos las clases necesarias
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Analytics.php';

// 2. Seguridad: Protegemos la API.
// Solo usuarios logueados pueden ver los datos.
// (Auth::init_session() está dentro de checkAuthentication)
Auth::checkAuthentication();

// 3. Establecemos la cabecera de respuesta como JSON
header('Content-Type: application/json');

// 4. Verificamos que se haya pedido un reporte
if (!isset($_GET['report'])) {
    echo json_encode(['success' => false, 'message' => 'No se especificó ningún reporte.']);
    exit;
}

$reportName = $_GET['report'];
$analytics = new Analytics();
$data = null;
$success = true;

// 5. El "switch" que pediste en el Plan Maestro
try {
    switch ($reportName) {
        case 'gastos_vs_presupuesto':
            $data = $analytics->getGastosVsPresupuesto();
            break;
            
        case 'tiempos_permanencia':
            $data = $analytics->getPermanenciaData();
            break;
            
        case 'rotacion_inventario':
            $data = $analytics->getRotacionData();
            break;

        case 'ranking_calidad_dato':
            $data = $analytics->getDataQualityRanking();
            break;

        default:
            $success = false;
            $data = "Reporte no válido: " . htmlspecialchars($reportName);
            break;
    }
} catch (Exception $e) {
    $success = false;
    $data = "Error al generar el reporte: " . $e->getMessage();
}

// 6. Devolvemos la respuesta
if ($success) {
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    echo json_encode(['success' => false, 'message' => $data]);
}
exit;
<?php
// sgal/modules/camaras/dashboard_vigilancia.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../core/Camera.php';

// Seguridad: Permiso para ver el dashboard.
Auth::enforcePermission('camaras_ver_dashboard');

$cameraHandler = new Camera();
$lista_camaras = $cameraHandler->getAllCameras();

require_once __DIR__ . '/../../templates/sidebar.php';
?>

<style>
    .camera-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 20px;
    }
    .camera-feed {
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #000;
        overflow: hidden;
    }
    .camera-feed h4 {
        margin: 0;
        padding: 10px;
        background-color: #333;
        color: white;
    }
    .camera-feed .stream-container {
        width: 100%;
        /* Mantiene el aspect ratio de 16:9 */
        padding-top: 56.25%; 
        position: relative;
    }
    .camera-feed .stream-container img,
    .camera-feed .stream-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<main class="content">
    <h2>Dashboard de Vigilancia en Vivo</h2>
    <p>Visualización de todas las cámaras activas en el sistema.</p>

    <div class="camera-grid">
        <?php foreach ($lista_camaras as $camara): ?>
            <div class="camera-feed">
                <h4><?php echo htmlspecialchars($camara['nombre_camara'] . ' - ' . $camara['ubicacion']); ?></h4>
                <div class="stream-container">
                    <?php
                    // --- LÓGICA DE INTEGRACIÓN ---
                    $url = $camara['url_stream_ip'];
                    
                    // 1. Detección de MJPEG (Motion JPEG)
                    // Es un formato simple que funciona en la etiqueta <img>
                    if (strpos(strtolower($url), '.mjpg') !== false || strpos(strtolower($url), 'mjpeg') !== false) {
                        echo '<img src="' . htmlspecialchars($url) . '" alt="Stream de ' . htmlspecialchars($camara['nombre_camara']) . '">';
                    }
                    
                    // 2. Detección de HLS (HTTP Live Streaming)
                    // Es un formato moderno que usa la etiqueta <video> y requiere un script JS (hls.js)
                    elseif (strpos(strtolower($url), '.m3u8') !== false) {
                        // Para que esto funcione, necesitas incluir hls.js en tu footer.php
                        echo '<video id="video-'.$camara['id'].'" controls autoplay muted></video>';
                        echo "<script>
                                if (Hls.isSupported()) {
                                    var video = document.getElementById('video-".$camara['id']."');
                                    var hls = new Hls();
                                    hls.loadSource('".htmlspecialchars($url)."');
                                    hls.attachMedia(video);
                                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                    video.src = '".htmlspecialchars($url)."';
                                }
                              </script>";
                    }

                    // 3. Caso para RTSP (Real Time Streaming Protocol)
                    // ¡IMPORTANTE! RTSP no se puede mostrar directamente en un navegador.
                    // Se necesita un servidor intermedio (como a WebRTC) para convertirlo a un formato compatible.
                    elseif (strpos(strtolower($url), 'rtsp://') !== false) {
                        echo '<div style="color:white; padding: 20px;">
                                <strong>Stream RTSP:</strong> '.htmlspecialchars($url).'<br>
                                <small>Este formato no es compatible con navegadores web directamente. Se requiere un servidor de streaming intermedio.</small>
                              </div>';
                    }

                    // 4. Caso por defecto (Intenta como si fuera una imagen)
                    else {
                        echo '<img src="' . htmlspecialchars($url) . '" alt="Intentando cargar stream...">';
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php
require_once __DIR__ . '/../../templates/footer.php';
?>
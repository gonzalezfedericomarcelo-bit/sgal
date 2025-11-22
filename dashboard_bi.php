<?php
// sgal/modules/bi/dashboard_bi.php

require_once __DIR__ . '/../../templates/header.php';
require_once __DIR__ . '/../../templates/sidebar.php'; // Incluimos el sidebar
require_once __DIR__ . '/../../core/Analytics.php'; // Necesario para la API

// 2. Seguridad: Permiso para ver el dashboard de BI.
Auth::enforcePermission('bi_ver_dashboard');
?>

<main class="content">

    <style>
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 columnas */
            gap: 20px;
            margin-top: 20px;
        }
        
        /* Contenedor de gráfico normal (ocupa 1 columna) */
        .chart-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: none; /* Quitamos el borde feo */
            
            /* --- SOLUCIÓN AL TAMAÑO GIGANTE / DESCUADRE --- */
            position: relative;
            height: 400px; /* Altura fija */
            max-width: 100%;
            /* --- FIN SOLUCIÓN --- */
        }

        /* Contenedor de gráfico full-width (ocupa 2 columnas) */
        .chart-container-full {
            grid-column: 1 / -1; /* Ocupa todo el ancho */
            position: relative;
            height: 400px;
            max-width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: none;
        }

        .chart-container h3,
        .chart-container-full h3 {
            margin-top: 0;
            text-align: center;
            font-size: 1.25rem;
            color: #1a237e; /* Usamos el color primario */
            font-weight: 600;
        }

        /* Responsive para pantallas chicas */
        @media (max-width: 900px) {
            .chart-grid {
                /* Hacemos 1 sola columna en celulares */
                grid-template-columns: 1fr;
            }
        }
    </style>

    <h1 class="h2 mb-4">Dashboard de Business Intelligence (BI)</h1>
    <p class="text-muted">Visualización de indicadores clave de rendimiento (KPIs) del sistema.</p>

    <div class="chart-grid">
        
        <div class="chart-container-full card">
            <h3 class="card-header">Gráfico 1: Gastos vs. Presupuesto (Últimos 12 meses)</h3>
            <div class="card-body">
                <canvas id="chartGastosVsPresupuesto"></canvas>
                <div id="chartGastosMsg" class="text-center text-muted p-5" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">No hay datos de gastos o presupuestos para mostrar.</div>
            </div>
        </div>

        <div class="chart-container card">
             <h3 class="card-header">Gráfico 2: Tiempos de Permanencia (Promedio)</h3>
            <div class="card-body">
                <canvas id="chartPermanencia"></canvas>
                <div id="chartPermanenciaMsg" class="text-center text-muted p-5" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">No hay datos de permanencia (accesos cerrados) para mostrar.</div>
            </div>
        </div>
        
        <div class="chart-container card">
             <h3 class="card-header">Gráfico 3: Top 5 Productos por Rotación</h3>
            <div class="card-body">
                <canvas id="chartRotacion"></canvas>
                <div id="chartRotacionMsg" class="text-center text-muted p-5" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">No hay movimientos de stock aprobados para mostrar.</div>
            </div>
        </div>
        
        <div class="chart-container-full card">
             <h3 class="card-header">Gráfico 4: Ranking de Calidad de Dato (Gamificación)</h3>
            <div class="card-body">
                <canvas id="chartRanking"></canvas>
                <div id="chartRankingMsg" class="text-center text-muted p-5" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">No hay acciones de usuarios registradas.</div>
            </div>
        </div>

    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Función auxiliar para llamar a nuestra API
     */
    async function fetchData(reportName) {
        try {
            const response = await fetch(`/sgal/api/bi_data.php?report=${reportName}`);
            if (!response.ok) {
                throw new Error(`Error de red: ${response.statusText}`);
            }
            const result = await response.json();
            if (!result.success) {
                console.error(`Error en la API para ${reportName}: ${result.message}`);
                return null;
            }
            return result.data;
        } catch (error) {
            console.error(`Error al cargar ${reportName}:`, error);
            return null;
        }
    }

    /**
     * Gráfico 1: Gastos vs. Presupuesto (Bar Chart)
     */
    async function renderChart1() {
        const data = await fetchData('gastos_vs_presupuesto');
        // Chequeo de datos (si no hay, muestra el mensaje de error)
        if (!data || data.length === 0) {
             document.getElementById('chartGastosVsPresupuesto').style.display = 'none';
             document.getElementById('chartGastosMsg').style.display = 'block';
             return;
        }
        const ctx = document.getElementById('chartGastosVsPresupuesto').getContext('2d');
        const labels = data.map(item => `${item.mes}/${item.anio}`);
        const gastosData = data.map(item => item.gastos);
        const presupuestoData = data.map(item => item.presupuesto);

        new Chart(ctx, {
            type: 'bar',
            data: { labels: labels, datasets: [
                    { label: 'Gastos Reales ($)', data: gastosData, backgroundColor: 'rgba(198, 40, 40, 0.7)', },
                    { label: 'Presupuesto ($)', data: presupuestoData, backgroundColor: 'rgba(26, 35, 126, 0.7)', }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, // <-- SOLUCIÓN TAMAÑO GIGANTE
                scales: { y: { beginAtZero: true } } 
            }
        });
    }

    /**
     * Gráfico 2: Tiempos de Permanencia (Pie Chart)
     */
    async function renderChart2() {
        const data = await fetchData('tiempos_permanencia');
        if (!data || data.length === 0) {
            document.getElementById('chartPermanencia').style.display = 'none';
            document.getElementById('chartPermanenciaMsg').style.display = 'block';
            return;
        }
        const ctx = document.getElementById('chartPermanencia').getContext('2d');
        const labels = data.map(item => item.tipo);
        const minutosData = data.map(item => item.avg_minutos.toFixed(2));

        new Chart(ctx, {
            type: 'pie',
            data: { labels: labels, datasets: [{
                    label: 'Promedio de Minutos',
                    data: minutosData,
                    backgroundColor: ['rgba(255, 160, 0, 0.7)', 'rgba(40, 167, 69, 0.7)', 'rgba(26, 35, 126, 0.7)'],
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false // <-- SOLUCIÓN TAMAÑO GIGANTE
            }
        });
    }

    /**
     * Gráfico 3: Rotación de Inventario (Doughnut Chart)
     */
    async function renderChart3() {
        const data = await fetchData('rotacion_inventario');
        if (!data || data.length === 0) {
            document.getElementById('chartRotacion').style.display = 'none';
            document.getElementById('chartRotacionMsg').style.display = 'block';
            return;
        }
        const ctx = document.getElementById('chartRotacion').getContext('2d');
        const labels = data.map(item => item.nombre);
        const movimientosData = data.map(item => item.total_movimientos);

        new Chart(ctx, {
            type: 'doughnut',
            data: { labels: labels, datasets: [{
                    label: 'N° de Movimientos',
                    data: movimientosData,
                    backgroundColor: ['rgba(26, 35, 126, 0.7)','rgba(40, 167, 69, 0.7)','rgba(255, 160, 0, 0.7)','rgba(198, 40, 40, 0.7)','rgba(100, 100, 100, 0.7)'],
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false // <-- SOLUCIÓN TAMAÑO GIGANTE
            }
        });
    }
    
    /**
     * Gráfico 4: Ranking Calidad (Bar Chart Horizontal)
     */
    async function renderChart4() {
        const data = await fetchData('ranking_calidad_dato');
        if (!data || data.length === 0) {
            document.getElementById('chartRanking').style.display = 'none';
            document.getElementById('chartRankingMsg').style.display = 'block';
            return;
        }
        const ctx = document.getElementById('chartRanking').getContext('2d');
        const labels = data.map(item => item.nombre_completo);
        const accionesData = data.map(item => item.total_acciones);

        new Chart(ctx, {
            type: 'bar',
            data: { labels: labels, datasets: [{
                    label: 'Total de Acciones de Calidad',
                    data: accionesData,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                }]
            },
            options: { 
                indexAxis: 'y', 
                responsive: true, 
                maintainAspectRatio: false // <-- SOLUCIÓN TAMAÑO GIGANTE
            }
        });
    }

    // --- Ejecutar todos los gráficos ---
    renderChart1();
    renderChart2();
    renderChart3();
    renderChart4();

});
</script>

</main>
<?php
// 4. Incluimos el footer
require_once __DIR__ . '/../../templates/footer.php';
?>
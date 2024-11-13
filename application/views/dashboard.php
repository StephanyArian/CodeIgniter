<!-- Inicio del contenido principal -->
<div class="container-fluid pt-4 px-4">
    <!-- Controles de fecha -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form id="date-range-form" class="d-flex">
                <input type="date" id="start-date" name="start_date" class="form-control me-2" value="<?php echo date('Y-m-d', strtotime('-30 days')); ?>">
                <input type="date" id="end-date" name="end_date" class="form-control me-2" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit" class="btn btn-primary">Aplicar</button>
            </form>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-chart-line fa-3x text-primary"></i>
                <div class="ms-3">
                    <p class="mb-2">Ventas Totales</p>
                    <h6 class="mb-0">Bs<span id="ventas-totales-monto">0.00</span></h6>
                    <small>Total: <span id="ventas-totales-num">0</span> ventas</small>
                </div>
            </div>
        </div>
       
    </div>

    <!-- Gráficos -->
    <div class="row g-4 mt-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Tendencia de Ventas</h6>
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Horas Más Ocupadas</h6>
                <canvas id="busiestHoursChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Mejor vendedor y última venta -->
    <div class="row g-4 mt-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Mejor Vendedor</h6>
                <div class="d-flex align-items-center py-3">
                  <?php
                  $foto = $this->session->userdata('Foto');
                  $imgSrc = empty($foto) ? base_url('uploads/usuarios/perfil.jpg') : base_url('uploads/usuarios/' . $foto);
                  ?>
                    <img id="top-seller-img" class="rounded-circle flex-shrink-0" src="<?php echo $imgSrc; ?>" alt="" style="width: 40px; height: 40px;">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0" id="top-seller-name"></h6>
                            <small id="top-seller-sales"></small>
                        </div>
                        <span id="top-seller-total"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-xl-6">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Última Venta</h6>
                <div class="d-flex align-items-center border-bottom py-3">
                    <div class="w-100 ms-3">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-0" id="last-sale-name"></h6>
                            <small id="last-sale-amount"></small>
                        </div>
                        <span id="last-sale-id"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Fin del contenido principal -->

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Variables globales para los gráficos
let salesTrendChart, busiestHoursChart;

// Función para inicializar los gráficos
function initCharts() {
    const ctxSalesTrend = document.getElementById('salesTrendChart').getContext('2d');
    salesTrendChart = new Chart(ctxSalesTrend, {
        type: 'line',
        data: { labels: [], datasets: [{ label: 'Ventas Diarias (Bs)', data: [], borderColor: 'rgba(75, 192, 192, 1)', tension: 0.1 }] },
        options: { scales: { y: { beginAtZero: true } } }
    });

    const ctxBusiestHours = document.getElementById('busiestHoursChart').getContext('2d');
    busiestHoursChart = new Chart(ctxBusiestHours, {
        type: 'bar',
        data: { labels: [], datasets: [{ label: 'Número de Ventas', data: [], backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(53, 162, 235, 1)', borderWidth: 1 }] },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Número de Ventas' } },
                x: { title: { display: true, text: 'Hora del Día' } }
            },
            plugins: {
                legend: { display: true, position: 'top' },
                title: { display: true, text: 'Horas Más Ocupadas' }
            }
        }
    });
}

// Función para actualizar los datos del dashboard
function updateDashboard(startDate, endDate) {
    $.ajax({
        url: '<?php echo base_url("/index.php/dashboard/refresh_data"); ?>',
        method: 'POST',
        data: { start_date: startDate, end_date: endDate },
        dataType: 'json',
        success: function(data) {
            // Actualizar estadísticas principales
            $('#ventas-totales-monto').text(numberFormat(data.stats.total_ventas));
            $('#ventas-totales-num').text(data.stats.num_ventas);
           
            // Actualizar gráficos
            updateChart(salesTrendChart, data.sales_trend, 'fecha', 'total_ventas');
            updateChart(busiestHoursChart, data.busiest_hours, 'hora', 'num_ventas', hour => hour + ':00');

            // Actualizar mejor vendedor
            $('#top-seller-name').text(data.top_seller.NombreUsuario || 'N/A');
            $('#top-seller-sales').text((data.top_seller.num_ventas || 0) + ' ventas');
            $('#top-seller-total').text('Total: Bs' + numberFormat(data.top_seller.total_ventas || 0));

            // Actualizar última venta
            $('#last-sale-name').text((data.last_sale.Nombre || 'N/A') + ' ' + (data.last_sale.PrimerApellido || ''));
            $('#last-sale-amount').text('Bs' + numberFormat(data.last_sale.Monto || 0));
            $('#last-sale-id').text('ID Venta: ' + (data.last_sale.idVenta || 'N/A'));
        }
    });
}

// Función auxiliar para actualizar gráficos
function updateChart(chart, data, labelKey, dataKey, labelTransform = x => x) {
    chart.data.labels = data.map(item => labelTransform(item[labelKey]));
    chart.data.datasets[0].data = data.map(item => item[dataKey]);
    chart.update();
}

// Función auxiliar para formatear números
function numberFormat(number) {
    return new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(number);
}

// Inicialización y eventos
$(document).ready(function() {
    initCharts();
    updateDashboard($('#start-date').val(), $('#end-date').val());

    $('#date-range-form').on('submit', function(e) {
        e.preventDefault();
        updateDashboard($('#start-date').val(), $('#end-date').val());
    });
});
</script>
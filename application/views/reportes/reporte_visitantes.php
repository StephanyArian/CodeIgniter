<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reporte de Visitantes</h3>
        </div>
        <div class="card-body">
            <!-- Formulario de fechas -->
            <form method="post" action="<?php echo base_url('reportes/visitantes'); ?>" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                                   value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                                   value="<?php echo $fecha_fin; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                <a href="<?php echo base_url('reportes/pdf_visitantes?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Gráfico de visitantes -->
            <div class="row mb-4">
                <div class="col-12">
                    <canvas id="graficoVisitantes" height="100"></canvas>
                </div>
            </div>

            <!-- Tabla de estadísticas -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabla-visitantes">
                    <thead>
                        <tr>
                            <th>Período</th>
                            <th>Adultos Mayores</th>
                            <th>Adultos</th>
                            <th>Infantes</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_adulto_mayor = 0;
                        $total_adulto = 0;
                        $total_infante = 0;
                        
                        foreach ($estadisticas as $key => $stats): 
                            if ($key !== 'estadisticas_generales'):
                                $total = $stats['total_adulto_mayor'] + $stats['total_adulto'] + $stats['total_infante'];
                                $total_adulto_mayor += $stats['total_adulto_mayor'];
                                $total_adulto += $stats['total_adulto'];
                                $total_infante += $stats['total_infante'];
                        ?>
                        <tr>
                            <td><?php echo $stats['periodo']; ?></td>
                            <td><?php echo $stats['total_adulto_mayor']; ?></td>
                            <td><?php echo $stats['total_adulto']; ?></td>
                            <td><?php echo $stats['total_infante']; ?></td>
                            <td><?php echo $total; ?></td>
                        </tr>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold">
                            <td>Total General</td>
                            <td><?php echo $total_adulto_mayor; ?></td>
                            <td><?php echo $total_adulto; ?></td>
                            <td><?php echo $total_infante; ?></td>
                            <td><?php echo $total_adulto_mayor + $total_adulto + $total_infante; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script>
$(document).ready(function() {
    var ctx = document.getElementById('graficoVisitantes').getContext('2d');
    var datos = <?php echo json_encode($estadisticas); ?>;
    var periodos = [];
    var adultosMayores = [];
    var adultos = [];
    var infantes = [];

    // Procesar datos para el gráfico
    Object.keys(datos).forEach(function(key) {
        if (key !== 'estadisticas_generales') {
            periodos.push(datos[key].periodo);
            adultosMayores.push(datos[key].total_adulto_mayor);
            adultos.push(datos[key].total_adulto);
            infantes.push(datos[key].total_infante);
        }
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: periodos,
            datasets: [{
                label: 'Adultos Mayores',
                data: adultosMayores,
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }, {
                label: 'Adultos',
                data: adultos,
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }, {
                label: 'Infantes',
                data: infantes,
                backgroundColor: 'rgba(75, 192, 192, 0.5)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    $('#tabla-visitantes').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });
});
</script>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reporte de Horarios</h3>
        </div>
        <div class="card-body">
            <!-- Formulario de fechas -->
            <form method="post" action="<?php echo base_url('reportes/horarios'); ?>" class="mb-4">
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
                                <a href="<?php echo base_url('reportes/pdf_horarios?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Gráfico de ocupación -->
            <div class="row mb-4">
                <div class="col-12">
                    <canvas id="graficoOcupacion" height="100"></canvas>
                </div>
            </div>

            <!-- Tabla de horarios -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabla-horarios">
                    <thead>
                        <tr>
                            <th>Horario</th>
                            <th>Capacidad Total</th>
                            <th>Ocupación</th>
                            <th>Disponibilidad</th>
                            <th>% Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): 
                            $disponibilidad = $horario['CapacidadTotal'] - $horario['Ocupacion'];
                            $porcentaje_ocupacion = ($horario['Ocupacion'] / $horario['CapacidadTotal']) * 100;
                        ?>
                        <tr>
                            <td><?php echo $horario['HoraInicio'] . ' - ' . $horario['HoraFin']; ?></td>
                            <td><?php echo $horario['CapacidadTotal']; ?></td>
                            <td><?php echo $horario['Ocupacion']; ?></td>
                            <td><?php echo $disponibilidad; ?></td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $porcentaje_ocupacion > 80 ? 'bg-danger' : ($porcentaje_ocupacion > 50 ? 'bg-warning' : 'bg-success'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $porcentaje_ocupacion; ?>%"
                                         aria-valuenow="<?php echo $porcentaje_ocupacion; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?php echo round($porcentaje_ocupacion, 1); ?>%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script para el gráfico -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('graficoOcupacion').getContext('2d');
    var datos = <?php echo json_encode($horarios); ?>;
    
    var labels = datos.map(function(item) {
        return item.HoraInicio + ' - ' + item.HoraFin;
    });
    
    var ocupacion = datos.map(function(item) {
        return item.Ocupacion;
    });
    
    var capacidad = datos.map(function(item) {
        return item.CapacidadTotal;
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ocupación',
                    data: ocupacion,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Capacidad Total',
                    data: capacidad,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Ocupación vs Capacidad Total por Horario'
                }
            }
        }
    });
});
</script>
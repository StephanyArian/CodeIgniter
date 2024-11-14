<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Reporte de Ventas</h3>
        </div>
        <div class="card-body">
            <!-- Formulario de fechas -->
            <form method="post" action="<?php echo base_url('reportes/ventas'); ?>" class="mb-4">
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
                                <a href="<?php echo base_url('reportes/pdf_ventas?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Resumen de totales -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Ventas</span>
                            <span class="info-box-number">Bs. <?php echo number_format($total_ventas, 2); ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-ticket-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tickets</span>
                            <span class="info-box-number"><?php echo number_format($total_tickets); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de ventas -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="tabla-ventas">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Visitante</th>
                            <th>CI/NIT</th>
                            <th>Tickets</th>
                            <th>Monto</th>
                            <th>Vendedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?php echo $venta['idVenta']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($venta['FechaCreacion'])); ?></td>
                            <td><?php echo $venta['Nombre'] . ' ' . $venta['PrimerApellido']; ?></td>
                            <td><?php echo $venta['CiNit']; ?></td>
                            <td><?php echo $venta['TotalTickets']; ?></td>
                            <td>Bs. <?php echo number_format($venta['Monto'], 2); ?></td>
                            <td><?php echo $venta['NombreUsuario']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<script>
$(document).ready(function() {
    $('#tabla-ventas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[1, "desc"]]
    });
});
</script>
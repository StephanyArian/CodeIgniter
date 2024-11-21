<style>
.totales-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
    margin-bottom: 2rem;
}

.card-total {
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-ventas {
    background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
}

.card-tickets {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
}

.card-total .icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 3rem;
    opacity: 0.2;
}

.total-title {
    font-size: 1.1rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.total-amount {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.total-subtitle {
    font-size: 0.875rem;
    opacity: 0.9;
}

.card-total:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}
</style>

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
            <div class="totales-container">
    <!-- Card Total Ventas -->
    <div class="card-total card-ventas">
        <div class="icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="total-title">Total Ventas</h3>
        <div class="total-amount">Bs. <?php echo number_format($total_ventas, 2); ?></div>
        <div class="total-subtitle">Período actual</div>
    </div>

    <!-- Card Total Tickets -->
    <div class="card-total card-tickets">
        <div class="icon">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h3 class="total-title">Total Tickets</h3>
        <div class="total-amount"><?php echo number_format($total_tickets); ?></div>
        <div class="total-subtitle">Tickets vendidos</div>
    </div>
</div>
           <!-- Tabla de ventas -->
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="tabla-ventas">
        <thead>
            <tr>
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
        "order": [[0, "desc"]] // Ahora ordenamos por la columna fecha que es la primera
    });
});
</script>
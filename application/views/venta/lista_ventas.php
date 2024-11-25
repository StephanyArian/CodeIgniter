<style>
/* Estilos personalizados para los badges */
.badge {
    font-size: 0.875rem;
    padding: 0.5em 0.8em;
    font-weight: 500;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

/* Haciendo los badges más visibles */
.estado-badge {
    display: inline-block;
    min-width: 80px;
    text-align: center;
    border-radius: 4px;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
</style>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <section class="content-header">
                    <div class="container-fluid">
                        <h1>Lista de Ventas</h1>
                    </div>
                </section>
                <div class="card-header">
                    <h3 class="card-title">Registro de todas las ventas</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('venta/nueva_venta') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Venta
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('mensaje')) : ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?= $this->session->flashdata('mensaje') ?>
                        </div>
                    <?php endif; ?>
                    
                    
<div class="table-responsive">
    <table id="ventasTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>CI/NIT</th>
                <th>Cantidad</th>
                <th>Monto Total</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas as $venta): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($venta['FechaCreacion'])) ?></td>
                    <td><?= $venta['Nombre'] . ' ' . $venta['PrimerApellido'] ?></td>
                    <td><?= $venta['CiNit'] ?></td>
                    <td><?= $venta['TotalTickets'] ?></td>
                    <td>Bs. <?= number_format($venta['Monto'], 2) ?></td>
                    <td><?= $venta['idUsuarios'] ?></td>
                    <td>
                        <?php if($venta['Estado'] == 1): ?>
                            <span class="badge estado-badge badge-success">Activo</span>
                        <?php else: ?>
                            <span class="badge estado-badge badge-danger">Anulado</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="<?= base_url('venta/detalle/'.$venta['idVenta']) ?>" 
                               class="btn btn-info btn-sm" 
                               title="Ver Detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('venta/imprimir/'.$venta['idVenta']) ?>" 
                            target="_blank" class="btn btn-success btn-sm"
                               title="Imprimir Comprobante">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="<?= base_url('venta/imprimir_tickets/'.$venta['idVenta']) ?>" 
                               class="btn btn-warning btn-sm"
                               title="Imprimir Tickets">
                                <i class="fas fa-ticket-alt"></i>
                            </a>
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
    </div>
</div>

<script>
$(document).ready(function() {
    $('#ventasTable').DataTable({
        "responsive": true,
        "order": [[0, "desc"]], // Ahora ordenamos por fecha que es la primera columna
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        }
    });
});
</script>
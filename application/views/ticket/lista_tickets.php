<style>
    .table th {
        white-space: nowrap;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .gap-2 {
        gap: 0.5rem!important;
    }
    .me-1 {
        margin-right: 0.25rem!important;
    }
    .me-2 {
        margin-right: 0.5rem!important;
    }
    .user-photo {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Lista de Tickets Vendidos</h2>
                    <a href="<?php echo site_url('ticket/nuevo'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nuevo Ticket
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nombre Visitante</th>
                                        <th>CI/NIT</th>
                                        <th>Fecha de Compra</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $index => $ticket): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                        <td><?php echo $ticket['Nombre'] . ' ' . $ticket['PrimerApellido'] . ' ' . $ticket['SegundoApellido']; ?></td>
                                        <td><?php echo $ticket['CiNit']; ?></td>
                                        <td><?php echo $ticket['FechaCreacion']; ?></td>
                                        <td class="text-end"><?php echo isset($ticket['Total']) ? number_format($ticket['Total'], 2) . ' Bs.' : 'N/A'; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo site_url('ticket/detalles/' . $ticket['idTickets']); ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye me-1"></i>Ver Detalles
                                            </a>
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
</div>
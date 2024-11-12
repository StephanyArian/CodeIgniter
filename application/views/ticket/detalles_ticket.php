<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Detalles del Ticket</h2>
                    <a href="<?php echo base_url('index.php/ticket'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title mb-4">Información del Ticket</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">ID Ticket:</dt>
                                    <dd class="col-sm-8"><?php echo $ticket['idTickets']; ?></dd>

                                    <dt class="col-sm-4">Tipo:</dt>
                                    <dd class="col-sm-8"><?php echo $ticket['tipo']; ?></dd>

                                    <dt class="col-sm-4">Precio:</dt>
                                    <dd class="col-sm-8">$<?php echo number_format($ticket['precio'], 2); ?></dd>

                                    <dt class="col-sm-4">Descripción:</dt>
                                    <dd class="col-sm-8"><?php echo $ticket['descripcion']; ?></dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h5 class="card-title mb-4">Información de Auditoría</h5>
                                <dl class="row">
                                    <dt class="col-sm-4">Estado:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge <?php echo $ticket['estado'] == 'activo' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo ucfirst($ticket['estado']); ?>
                                        </span>
                                    </dd>

                                    <dt class="col-sm-4">Usuario Auditor:</dt>
                                    <dd class="col-sm-8"><?php echo $ticket['NombreUsuario'] . ' ' . $ticket['ApellidoUsuario']; ?></dd>

                                    <dt class="col-sm-4">Última Actualización:</dt>
                                    <dd class="col-sm-8"><?php echo $ticket['fecha_actualizacion']; ?></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        white-space: nowrap;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table-responsive {
        margin: -1rem;
    }

    .alert {
        margin-bottom: 1.5rem;
    }

    .gap-2 {
        gap: 0.5rem!important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.04);
    }

    dl.row {
        margin-bottom: 0;
    }

    dt {
        font-weight: 600;
    }

    .badge {
        padding: 0.5em 0.75em;
    }
</style>
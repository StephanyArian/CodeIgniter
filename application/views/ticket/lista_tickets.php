
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


<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <!-- Header con título y botones -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Lista de Tickets</h2>
                    <div class="d-flex gap-2">
                        <a href="<?php echo base_url(); ?>index.php/ticket/agregar" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Agregar Ticket
                        </a>
                    </div>
                </div>

                <?php if($this->session->flashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('mensaje'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Card con la tabla -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Tipo</th>
                                        <th>Precio</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Usuario Auditor</th>
                                        <th>Última Actualización</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contador = 1;
                                    foreach ($tickets as $ticket) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $contador; ?></td>
                                        <td><?php echo $ticket['tipo']; ?></td>
                                        <td><?php echo number_format($ticket['precio'], 2); ?></td>
                                        <td><?php echo $ticket['descripcion']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $ticket['estado'] == 'activo' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo ucfirst($ticket['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $ticket['NombreUsuario'] . ' ' . $ticket['ApellidoUsuario']; ?></td>
                                        <td><?php echo $ticket['fecha_actualizacion']; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('index.php/ticket/detalles/' . $ticket['idTickets']); ?>" 
                                               class="btn btn-sm btn-info me-2" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye me-1"></i>
                                            </a>
                                            <a href="<?php echo base_url('index.php/ticket/modificar/' . $ticket['idTickets']); ?>" 
                                               class="btn btn-sm btn-warning me-2" 
                                               title="Editar">
                                                <i class="fas fa-edit me-1"></i>
                                            </a>
                                            <a href="<?php echo base_url('index.php/ticket/eliminarbd/' . $ticket['idTickets']); ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('¿Está seguro de eliminar este ticket?');"
                                               title="Eliminar">
                                                <i class="fas fa-trash-alt me-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $contador++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

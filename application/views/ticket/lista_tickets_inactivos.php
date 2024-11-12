<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <!-- Header con título y botón de regreso -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Tickets Inactivos</h2>
                    <div class="d-flex gap-2">
                        <a href="<?php echo base_url(); ?>index.php/ticket" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Tickets Activos
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
                                            <span class="badge bg-danger">
                                                Inactivo
                                            </span>
                                        </td>
                                        <td><?php echo $ticket['NombreUsuario'] . ' ' . $ticket['ApellidoUsuario']; ?></td>
                                        <td><?php echo $ticket['fecha_actualizacion']; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('index.php/ticket/activar/' . $ticket['idTickets']); ?>" 
                                               class="btn btn-sm btn-success" 
                                               onclick="return confirm('¿Está seguro de activar este ticket?');"
                                               title="Activar">
                                                <i class="fas fa-check-circle me-1"></i>Activar
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

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Lista de Visitantes</h2>
                    <div class="d-flex gap-2">
                        <a href="<?php echo site_url('visitante/agregar'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Agregar Visitante
                        </a>
                        <a href="<?php echo site_url('venta/buscar_visitante'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retornar a Venta
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Nro</th>
                                        <th>Nombre</th>
                                        <th>Primer Apellido</th>
                                        <th>Segundo Apellido</th>
                                        <th>CI/NIT</th>
                                        <th>Número Celular</th>
                                        <th>Email</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $contador = 1;
                                    foreach ($visitantes as $visitante){ ?>
                                        <tr>
                                            <td class="text-center"><?php echo $contador; ?></td>
                                            <td><?php echo $visitante['Nombre']; ?></td>
                                            <td><?php echo $visitante['PrimerApellido']; ?></td>
                                            <td><?php echo $visitante['SegundoApellido']; ?></td>
                                            <td><?php echo $visitante['CiNit']; ?></td>
                                            <td><?php echo $visitante['NroCelular']; ?></td>
                                            <td><?php echo $visitante['Email']; ?></td>
                                            <td class="text-center">
                                                <span class="badge <?php echo $visitante['Estado'] == 1 ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo $visitante['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo site_url('visitante/editar/' . $visitante['idVisitante']); ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo site_url('visitante/eliminar/' . $visitante['idVisitante']); ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('¿Está seguro de eliminar este visitante?');"
                                                       title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
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
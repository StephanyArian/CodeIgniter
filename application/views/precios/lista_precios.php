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
<!-- lista_precios.php -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Precios Activos</h2>
                  <!--  <a href="<?php echo site_url('precios/agregar'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Agregar Nuevo Precio
                    </a>-->
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Tipo</th>
                                        <th class="text-end">Precio</th>
                                        <th>Última Actualización</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($precios as $index => $precio): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $index + 1; ?></td>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $precio['tipo'])); ?></td>
                                        <td class="text-end"><?php echo number_format($precio['precio'], 2) . ' Bs.'; ?></td>
                                        <td><?php echo $precio['fecha_actualizacion']; ?></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo site_url('precios/editar/'.$precio['id']); ?>" 
                                                   class="btn btn-warning btn-sm" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo site_url('precios/eliminar/'.$precio['id']); ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   onclick="return confirm('¿Está seguro de que desea desactivar este precio?');"
                                                   title="Desactivar">
                                                    <i class="fas fa-times"></i>
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
</div>
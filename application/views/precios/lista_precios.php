<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2 class="mb-4">Lista de Precios Activos</h2>
                <a href="<?php echo site_url('precios/agregar'); ?>" class="btn btn-primary mb-3">Agregar Nuevo Precio</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($precios as $precio): ?>
                        <tr>
                            <td><?php echo $precio['id']; ?></td>
                            <td><?php echo ucfirst(str_replace('_', ' ', $precio['tipo'])); ?></td>
                            <td><?php echo $precio['precio']; ?></td>
                            <td><?php echo $precio['fecha_actualizacion']; ?></td>
                            <td>
                                <a href="<?php echo site_url('precios/editar/'.$precio['id']); ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="<?php echo site_url('precios/eliminar/'.$precio['id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de que desea desactivar este precio?');">Desactivar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
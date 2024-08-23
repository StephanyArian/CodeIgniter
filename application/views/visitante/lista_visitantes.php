<div class="container">
    <h2>Lista de Visitantes</h2>
    <a href="<?php echo site_url('visitante/agregar'); ?>" class="btn btn-primary mb-3">Agregar Visitante</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>CI/NIT</th>
                <th>Número Celular</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Fecha Creación</th>
                <th>Fecha Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visitantes as $visitante): ?>
            <tr>
                <td><?php echo $visitante['idVisitante']; ?></td>
                <td><?php echo $visitante['Nombre']; ?></td>
                <td><?php echo $visitante['PrimerApellido']; ?></td>
                <td><?php echo $visitante['SegundoApellido']; ?></td>
                <td><?php echo $visitante['CiNit']; ?></td>
                <td><?php echo $visitante['NroCelular']; ?></td>
                <td><?php echo $visitante['Email']; ?></td>
                <td><?php echo $visitante['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                <td><?php echo $visitante['FechaCreacion']; ?></td>
                <td><?php echo $visitante['FechaActualizacion']; ?></td>
                <td>
                    <a href="<?php echo site_url('visitante/editar/' . $visitante['idVisitante']); ?>" class="btn btn-warning">Editar</a>
                    <a href="<?php echo site_url('visitante/eliminar/' . $visitante['idVisitante']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este visitante?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

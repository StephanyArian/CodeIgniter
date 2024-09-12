

    <!-- Inicio del contenido principal -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
<div class="bg-light rounded h-100 p-4">
    <h2>Lista de Visitantes</h2>
    <a href="<?php echo site_url('visitante/agregar'); ?>" class="btn btn-primary mb-3">Agregar Visitante</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Nombre</th>
                <th>Primer Apellido</th>
                <th>Segundo Apellido</th>
                <th>CI/NIT</th>
                <th>Número Celular</th>
                <th>Email</th>
                <th>Estado</th>
                
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
             $contador = 1;
             foreach ($visitantes as $visitante){ ?>
            <tr>
                <td><?php echo $contador; ?></td>
                <td><?php echo $visitante['Nombre']; ?></td>
                <td><?php echo $visitante['PrimerApellido']; ?></td>
                <td><?php echo $visitante['SegundoApellido']; ?></td>
                <td><?php echo $visitante['CiNit']; ?></td>
                <td><?php echo $visitante['NroCelular']; ?></td>
                <td><?php echo $visitante['Email']; ?></td>
                <td><?php echo $visitante['Estado'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
             
                <td>
                    <a href="<?php echo site_url('visitante/editar/' . $visitante['idVisitante']); ?>" class="btn btn-warning"> <i class="fas fa-edit"></i> </a>
                    <a href="<?php echo site_url('visitante/eliminar/' . $visitante['idVisitante']); ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar este visitante?');">
                    <i class="fas fa-trash-alt"></i>
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
  
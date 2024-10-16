<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Horarios Disponibles</h2>
                <a href="<?php echo site_url('horario/agregar'); ?>" class="btn btn-primary mb-3">Agregar Nuevo Horario</a>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora de Entrada</th>
                            <th>Hora de Cierre</th>
                            <th>Capacidad Máxima</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($horario['Dia'])); ?></td>
                            <td><?php echo $horario['HoraEntrada']; ?></td>
                            <td><?php echo $horario['HoraCierre']; ?></td>
                            <td><?php echo $horario['MaxVisitantes']; ?></td>
                            <td><?php echo $horario['Estado'] ? 'Abierto' : 'Cerrado'; ?></td>
                            <td>
                                <a href="<?php echo site_url('horario/editar/' . $horario['idHorarios']); ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="<?php echo site_url('horario/eliminar/' . $horario['idHorarios']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de querer eliminar este horario?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
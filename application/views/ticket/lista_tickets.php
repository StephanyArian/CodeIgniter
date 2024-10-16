<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Lista de Tickets Vendidos</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Ticket</th>
                            <th>Nombre Visitante</th>
                            <th>CI/NIT</th>
                            <th>Fecha de Compra</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket['idTickets']; ?></td>
                            <td><?php echo $ticket['Nombre'] . ' ' . $ticket['PrimerApellido'] . ' ' . $ticket['SegundoApellido']; ?></td>
                            <td><?php echo $ticket['CiNit']; ?></td>
                            <td><?php echo $ticket['FechaCreacion']; ?></td>
                            <td><?php echo isset($ticket['Total']) ? $ticket['Total'] : 'N/A'; ?></td>
                            
                            <td>
                                <a href="<?php echo site_url('ticket/detalles/' . $ticket['idTickets']); ?>" class="btn btn-info">Ver Detalles</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
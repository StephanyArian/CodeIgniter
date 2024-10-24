<!-- detalles_ticket.php -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Detalles del Ticket</h2>
                <?php if (isset($ticket) && !empty($ticket)): ?>
                    <h3>Información del Visitante</h3>
                    <p><strong>Nombre:</strong> <?php echo isset($ticket['Nombre']) ? $ticket['Nombre'] : ''; ?> 
                        <?php echo isset($ticket['PrimerApellido']) ? $ticket['PrimerApellido'] : ''; ?> 
                        <?php echo isset($ticket['SegundoApellido']) ? $ticket['SegundoApellido'] : ''; ?></p>
                    <p><strong>CI/NIT:</strong> <?php echo isset($ticket['CiNit']) ? $ticket['CiNit'] : 'No especificado'; ?></p>
                    <p><strong>Email:</strong> <?php echo isset($ticket['Email']) ? $ticket['Email'] : 'No especificado'; ?></p>
                    <p><strong>Celular:</strong> <?php echo isset($ticket['NroCelular']) ? $ticket['NroCelular'] : 'No especificado'; ?></p>

                    <h3>Información del Ticket</h3>
                    <p><strong>ID Ticket:</strong> <?php echo isset($ticket['idTickets']) ? $ticket['idTickets'] : 'No disponible'; ?></p>
                    <p><strong>Descripción:</strong> 
                        <?php 
                        if (isset($ticket['tipo'])) {
                            switch ($ticket['tipo']) {
                                case 'adulto_mayor':
                                    echo "Es para 61-80";
                                    break;
                                case 'adulto':
                                    echo "Es para 18-60";
                                    break;
                                case 'infante':
                                    echo "Es para 0-17";
                                    break;
                                default:
                                    echo "No especificado";
                            }
                        } else {
                            echo "No especificado";
                        }
                        ?>
                    </p>
                    <p><strong>Tipo de Ticket:</strong> <?php echo isset($ticket['tipo']) ? ucfirst(str_replace('_', ' ', $ticket['tipo'])) : 'No especificado'; ?></p>
                    <p><strong>Precio:</strong> <?php echo isset($ticket['precio']) ? number_format($ticket['precio'], 2) . ' Bs.' : 'No especificado'; ?></p>
                    <p><strong>Fecha de Compra:</strong> <?php echo isset($ticket['FechaCreacion']) ? date('d/m/Y H:i', strtotime($ticket['FechaCreacion'])) : 'No especificada'; ?></p>
                    <p><strong>Estado:</strong> <?php echo isset($ticket['estado']) ? $ticket['estado'] : 'No especificado'; ?></p>
                    <p><strong>Total:</strong> <?php echo isset($ticket['Total']) ? number_format($ticket['Total'], 2) . ' Bs.' : 'No especificado'; ?></p>
                <?php else: ?>
                    <div class="alert alert-warning">
                        No se encontraron datos del ticket.
                    </div>
                <?php endif; ?>
                
                <a href="<?php echo base_url('index.php/ticket'); ?>" class="btn btn-secondary mt-3">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Detalles del Ticket</h2>
                <h3>Información del Visitante</h3>
                <p><strong>Nombre:</strong> <?php echo $ticket['Nombre'] . ' ' . $ticket['PrimerApellido'] . ' ' . $ticket['SegundoApellido']; ?></p>
                <p><strong>CI/NIT:</strong> <?php echo $ticket['CiNit']; ?></p>
                <p><strong>Email:</strong> <?php echo $ticket['Email']; ?></p>
                <p><strong>Celular:</strong> <?php echo $ticket['NroCelular']; ?></p>

                <h3>Información del Ticket</h3>
                <p><strong>ID Ticket:</strong> <?php echo $ticket['idTickets']; ?></p>
                <p><strong>Fecha de Compra:</strong> <?php echo $ticket['FechaCreacion']; ?></p>
                <p><strong>Cantidad Adulto Mayor:</strong> <?php echo $ticket['CantAdultoMayor']; ?></p>
                <p><strong>Cantidad Adulto:</strong> <?php echo $ticket['CantAdulto']; ?></p>
                <p><strong>Cantidad Infante:</strong> <?php echo $ticket['CantInfante']; ?></p>
                <p><strong>Total:</strong> <?php echo $ticket['SubTotal']; ?></p>
            </div>
        </div>
    </div>
</div>
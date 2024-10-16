<!-- application/views/visitante/confirmar_venta.php -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Confirmar Venta de Tickets</h2>
                
                <h3>Resumen de la venta</h3>
                <p><strong>Nombre:</strong> <?php echo $venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido']; ?></p>
                <p><strong>CI/NIT:</strong> <?php echo $venta['CiNit']; ?></p>
                <p><strong>Número Celular:</strong> <?php echo $venta['NroCelular']; ?></p>
                <p><strong>Email:</strong> <?php echo $venta['Email']; ?></p>
                
                <h3>Detalles de la venta:</h3>
<p>Adultos Mayores: <?php echo $venta['CantAdultoMayor']; ?></p>
<p>Adultos: <?php echo $venta['CantAdulto']; ?></p>
<p>Infantes: <?php echo $venta['CantInfante']; ?></p>

<p><strong>Subtotal:</strong> <?php echo number_format($subtotal, 2); ?> Bs.</p>

<p><strong>Comentario:</strong> <?php echo $venta['Comentario']; ?></p>
                
<?php echo form_open('visitante/procesar_venta'); ?>
<button type="submit" class="btn btn-primary">Confirmar Venta</button>
<?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
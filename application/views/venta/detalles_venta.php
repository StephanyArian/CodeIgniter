<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Detalles de la Venta</h2>
                
                <h3>Información del Visitante</h3>
                <p><strong>Nombre:</strong> <?php echo $venta['Nombre'] . ' ' . $venta['PrimerApellido'] . ' ' . $venta['SegundoApellido']; ?></p>
                <p><strong>CI/NIT:</strong> <?php echo $venta['CiNit']; ?></p>
                <p><strong>Email:</strong> <?php echo $venta['Email']; ?></p>
                <p><strong>Celular:</strong> <?php echo $venta['NroCelular']; ?></p>

                <h3>Información de la Venta</h3>
                <p><strong>ID Venta:</strong> <?php echo $venta['idVenta']; ?></p>
                <p><strong>Fecha de Venta:</strong> <?php echo date('d/m/Y H:i', strtotime($venta['FechaCreacion'])); ?></p>
                
                <h4>Detalle de Tickets</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Adulto Mayor</td>
                            <td><?php echo $venta['CantAdultoMayor']; ?></td>
                            <td><?php echo number_format($venta['PrecioAdultoMayor'], 2) . ' Bs.'; ?></td>
                            <td><?php echo number_format($venta['CantAdultoMayor'] * $venta['PrecioAdultoMayor'], 2) . ' Bs.'; ?></td>
                        </tr>
                        <tr>
                            <td>Adulto</td>
                            <td><?php echo $venta['CantAdulto']; ?></td>
                            <td><?php echo number_format($venta['PrecioAdulto'], 2) . ' Bs.'; ?></td>
                            <td><?php echo number_format($venta['CantAdulto'] * $venta['PrecioAdulto'], 2) . ' Bs.'; ?></td>
                        </tr>
                        <tr>
                            <td>Infante</td>
                            <td><?php echo $venta['CantInfante']; ?></td>
                            <td><?php echo number_format($venta['PrecioInfante'], 2) . ' Bs.'; ?></td>
                            <td><?php echo number_format($venta['CantInfante'] * $venta['PrecioInfante'], 2) . ' Bs.'; ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th><?php echo number_format($venta['Monto'], 2) . ' Bs.'; ?></th>
                        </tr>
                    </tfoot>
                </table>

                <p><strong>Comentario:</strong> <?php echo $venta['Comentario']; ?></p>

                <a href="<?php echo base_url('index.php/venta/imprimir/'.$venta['idVenta']); ?>" class="btn btn-primary">Imprimir Comprobante</a>
                <a href="<?php echo base_url('index.php/Venta/index#'); ?>" class="btn btn-secondary">Volver a la lista</a>
            </div>
        </div>
    </div>
</div>
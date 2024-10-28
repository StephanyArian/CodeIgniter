<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Detalles de la Venta</h2>
                
                <?php if (isset($venta) && !empty($venta)): ?>
                    <h3>Información del Visitante</h3>
                    <p><strong>Nombre:</strong> 
                        <?php 
                        $nombreCompleto = array_filter([
                            $venta['Nombre'] ?? '',
                            $venta['PrimerApellido'] ?? '',
                            $venta['SegundoApellido'] ?? ''
                        ]);
                        echo !empty($nombreCompleto) ? implode(' ', $nombreCompleto) : 'No especificado';
                        ?>
                    </p>
                    <p><strong>CI/NIT:</strong> <?php echo $venta['CiNit'] ?? 'No especificado'; ?></p>
                    <p><strong>Email:</strong> <?php echo $venta['Email'] ?? 'No especificado'; ?></p>
                    <p><strong>Celular:</strong> <?php echo $venta['NroCelular'] ?? 'No especificado'; ?></p>

                    <h3>Información de la Venta</h3>
                    <p><strong>ID Venta:</strong> <?php echo $venta['idVenta'] ?? 'No disponible'; ?></p>
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
                            <?php if (isset($venta['CantAdultoMayor']) && $venta['CantAdultoMayor'] > 0): ?>
                            <tr>
                                <td>Adulto Mayor</td>
                                <td><?php echo $venta['CantAdultoMayor']; ?></td>
                                <td><?php echo number_format($venta['PrecioAdultoMayor'], 2) . ' Bs.'; ?></td>
                                <td><?php echo number_format($venta['CantAdultoMayor'] * $venta['PrecioAdultoMayor'], 2) . ' Bs.'; ?></td>
                            </tr>
                            <?php endif; ?>
                            
                            <?php if (isset($venta['CantAdulto']) && $venta['CantAdulto'] > 0): ?>
                            <tr>
                                <td>Adulto</td>
                                <td><?php echo $venta['CantAdulto']; ?></td>
                                <td><?php echo number_format($venta['PrecioAdulto'], 2) . ' Bs.'; ?></td>
                                <td><?php echo number_format($venta['CantAdulto'] * $venta['PrecioAdulto'], 2) . ' Bs.'; ?></td>
                            </tr>
                            <?php endif; ?>
                            
                            <?php if (isset($venta['CantInfante']) && $venta['CantInfante'] > 0): ?>
                            <tr>
                                <td>Infante</td>
                                <td><?php echo $venta['CantInfante']; ?></td>
                                <td><?php echo number_format($venta['PrecioInfante'], 2) . ' Bs.'; ?></td>
                                <td><?php echo number_format($venta['CantInfante'] * $venta['PrecioInfante'], 2) . ' Bs.'; ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th><?php echo isset($venta['Monto']) ? number_format($venta['Monto'], 2) . ' Bs.' : '0.00 Bs.'; ?></th>
                            </tr>
                        </tfoot>
                    </table>

                    <?php if (!empty($venta['Comentario'])): ?>
                        <p><strong>Comentario:</strong> <?php echo $venta['Comentario']; ?></p>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="alert alert-warning">
                        No se encontraron datos de la venta.
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="<?php echo base_url('index.php/venta/imprimir/'.($venta['idVenta'] ?? '')); ?>" class="btn btn-primary">Imprimir Comprobante</a>
                    <a href="<?php echo base_url('index.php/Venta'); ?>" class="btn btn-secondary">Volver a la lista</a>
                </div>
            </div>
        </div>
    </div>
</div>
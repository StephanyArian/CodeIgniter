<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2 class="mb-4">Ventas</h2>
                
                <?php if($this->session->flashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('mensaje'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Venta</th>
                                <th>Visitante</th>
                                <th>CI/NIT</th>
                                <th>Fecha</th>
                                <th>Adulto Mayor</th>
                                <th>Adulto</th>
                                <th>Infante</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $venta): ?>
                                <tr>
                                    <td><?php echo $venta['idVenta']; ?></td>
                                    <td><?php echo $venta['Nombre'] . ' ' . $venta['PrimerApellido']; ?></td>
                                    <td><?php echo $venta['CiNit']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($venta['FechaCreacion'])); ?></td>
                                    <td><?php 
                                       // Asegurarse de que el valor no sea null y sea mayor que 0
                                       echo (!is_null($venta['CantAdultoMayor']) && $venta['CantAdultoMayor'] > 0) ? 
                                       $venta['CantAdultoMayor'] : '0'; 
                                    ?></td>
                                    <td><?php 
                                       echo (!is_null($venta['CantAdulto']) && $venta['CantAdulto'] > 0) ? 
                                      $venta['CantAdulto'] : '0'; 
                                    ?></td>
                                    <td><?php 
                                      echo (!is_null($venta['CantInfante']) && $venta['CantInfante'] > 0) ? 
                                      $venta['CantInfante'] : '0'; 
                                    ?></td>
                                    <td><?php echo number_format($venta['Monto'], 2) . ' Bs.'; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('index.php/venta/detalle/'.$venta['idVenta']); ?>" class="btn btn-sm btn-info">Ver detalle</a>
                                        <a href="<?php echo base_url('index.php/venta/imprimir/'.$venta['idVenta']); ?>" class="btn btn-sm btn-secondary">Imprimir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <a href="<?php echo base_url('index.php/Venta/buscar_visitante'); ?>" class="btn btn-primary mt-3">Nueva Venta</a>
            </div>
        </div>
    </div>
</div>
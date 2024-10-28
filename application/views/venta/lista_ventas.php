<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Ventas</h2>
                    <a href="<?php echo base_url('index.php/Venta/buscar_visitante'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Venta
                    </a>
                </div>
                
                <?php if($this->session->flashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('mensaje'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Visitante</th>
                                        <th>CI/NIT</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Adulto Mayor</th>
                                        <th class="text-center">Adulto</th>
                                        <th class="text-center">Infante</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ventas as $index => $venta): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $index + 1; ?></td>
                                            <td><?php echo $venta['Nombre'] . ' ' . $venta['PrimerApellido']; ?></td>
                                            <td><?php echo $venta['CiNit']; ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($venta['FechaCreacion'])); ?></td>
                                            <td class="text-center"><?php 
                                               echo (!is_null($venta['CantAdultoMayor']) && $venta['CantAdultoMayor'] > 0) ? 
                                               $venta['CantAdultoMayor'] : '0'; 
                                            ?></td>
                                            <td class="text-center"><?php 
                                               echo (!is_null($venta['CantAdulto']) && $venta['CantAdulto'] > 0) ? 
                                               $venta['CantAdulto'] : '0'; 
                                            ?></td>
                                            <td class="text-center"><?php 
                                               echo (!is_null($venta['CantInfante']) && $venta['CantInfante'] > 0) ? 
                                               $venta['CantInfante'] : '0'; 
                                            ?></td>
                                            <td class="text-right"><?php echo number_format($venta['Monto'], 2) . ' Bs.'; ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url('index.php/venta/detalle/'.$venta['idVenta']); ?>" 
                                                   class="btn btn-sm btn-info me-2">
                                                   <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                                <a href="<?php echo base_url('index.php/venta/imprimir/'.$venta['idVenta']); ?>" 
                                                   class="btn btn-sm btn-secondary">
                                                   <i class="fas fa-print me-1"></i>Imprimir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
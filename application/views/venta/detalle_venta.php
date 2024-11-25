<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1>Detalle de Venta #<?= $venta_details[0]['idVenta'] ?></h1>
                            <a href="<?= base_url('venta') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </section>

                <div class="row mt-4">
                    <!-- Información de la Venta -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Información de la Venta</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Fecha de Venta:</th>
                                        <td><?= date('d/m/Y H:i', strtotime($venta_details[0]['FechaCreacion'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Vendedor:</th>
                                        <td><?= $venta_details[0]['NombreVendedor'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Monto Total:</th>
                                        <td>Bs. <?= number_format($venta_details[0]['Monto'], 2) ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Visitante -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Información del Visitante</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">Nombre:</th>
                                        <td><?= $venta_details[0]['Nombre'] . ' ' . $venta_details[0]['PrimerApellido'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>CI/NIT:</th>
                                        <td><?= $venta_details[0]['CiNit'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Celular:</th>
                                        <td><?= $venta_details[0]['NroCelular'] ?? 'No registrado' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?= $venta_details[0]['Email'] ?? 'No registrado' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

               

                <!-- Detalle de Tickets -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Detalle de Tickets</h3>
                            </div>
                            
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($venta_details as $detalle): ?>
            <tr>
                <td><?= $detalle['TipoTicket'] ?></td>
                <td><?= $detalle['DescripcionTicket'] ?></td>
                <td><?= $detalle['CantidadTotal'] ?></td>
                <td>Bs. <?= number_format($detalle['precio'], 2) ?></td>
                <td>Bs. <?= number_format($detalle['precio'] * $detalle['CantidadTotal'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th>Bs. <?= number_format($venta_details[0]['Monto'], 2) ?></th>
            </tr>
        </tfoot>
    </table>
</div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <a href="<?= base_url('venta/imprimir/'.$venta_details[0]['idVenta']) ?>" 
                           class="btn btn-success me-2"
                           target="_blank">
                            <i class="fas fa-print"></i> Imprimir Comprobante
                        </a>
                        <a href="<?= base_url('venta/imprimir_tickets/'.$venta_details[0]['idVenta']) ?>" 
                           class="btn btn-warning me-2">
                            <i class="fas fa-ticket-alt"></i> Imprimir Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


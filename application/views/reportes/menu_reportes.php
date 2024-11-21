<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="container-fluid">
    <!-- Reporte de Ventas -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Reporte de Ventas</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('reportes/ventas'); ?>" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                                   value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                                   value="<?php echo $fecha_fin; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Ver Reporte</button>
                                <a href="<?php echo base_url('reportes/pdf_ventas?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reporte de Visitantes -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Reporte de Visitantes</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('reportes/visitantes'); ?>" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_inicio_visitantes">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio_visitantes" class="form-control" 
                                   value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_fin_visitantes">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin_visitantes" class="form-control" 
                                   value="<?php echo $fecha_fin; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Ver Reporte</button>
                                <a href="<?php echo base_url('reportes/pdf_visitantes?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reporte de Horarios -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Reporte de Horarios</h3>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo base_url('reportes/horarios'); ?>" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_inicio_horarios">Fecha Inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio_horarios" class="form-control" 
                                   value="<?php echo $fecha_inicio; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fecha_fin_horarios">Fecha Fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin_horarios" class="form-control" 
                                   value="<?php echo $fecha_fin; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Ver Reporte</button>
                                <a href="<?php echo base_url('reportes/pdf_horarios?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin); ?>" 
                                   class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> Exportar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
        </div>
    </div>
</div>

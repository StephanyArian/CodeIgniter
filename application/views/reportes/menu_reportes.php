<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Reportes Disponibles</h6>
                <div class="m-n2">
                    <a href="<?php echo base_url('index.php/Reportes_controller/ocupacion_horarios'); ?>" class="btn btn-outline-primary m-2">
                        <i class="far fa-clock me-2"></i>Ocupación de Horarios
                    </a>
                    <a href="<?php echo base_url('index.php/Reportes_controller/estructura_precios'); ?>" class="btn btn-outline-primary m-2">
                        <i class="fas fa-dollar-sign me-2"></i>Estructura de Precios
                    </a>
                    <a href="<?php echo base_url('index.php/Reportes_controller/ventas_tickets'); ?>" class="btn btn-outline-primary m-2">
                        <i class="fas fa-ticket-alt me-2"></i>Ventas de Tickets
                    </a>
                    <a href="<?php echo base_url('index.php/Reportes_controller/estadisticas_visitantes'); ?>" class="btn btn-outline-primary m-2">
                        <i class="fas fa-users me-2"></i>Estadísticas de Visitantes
                    </a>
                    <a href="<?php echo base_url('index.php/Usuario/lista_usuarios'); ?>" class="btn btn-outline-primary m-2">
                        <i class="fas fa-user me-2"></i>Lista de Usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
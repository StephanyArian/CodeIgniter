<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4 shadow-sm">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    <h6 class="mb-0">Reportes Disponibles</h6>
                </div>
                
                <div class="report-grid">
                    <a href="<?php echo base_url('index.php/Reportes_controller/ocupacion_horarios'); ?>" 
                       class="btn btn-light report-btn">
                        <i class="far fa-clock me-2"></i>
                        <span>Ocupación de Horarios</span>
                    </a>
                    
                    <a href="<?php echo base_url('index.php/Reportes_controller/estructura_precios'); ?>" 
                       class="btn btn-light report-btn">
                        <i class="fas fa-dollar-sign me-2"></i>
                        <span>Estructura de Precios</span>
                    </a>
                    
                    <a href="<?php echo base_url('index.php/Reportes_controller/ventas_tickets'); ?>" 
                       class="btn btn-light report-btn">
                        <i class="fas fa-ticket-alt me-2"></i>
                        <span>Ventas de Tickets</span>
                    </a>
                    
                    <a href="<?php echo base_url('index.php/Reportes_controller/estadisticas_visitantes'); ?>" 
                       class="btn btn-light report-btn">
                        <i class="fas fa-users me-2"></i>
                        <span>Estadísticas de Visitantes</span>
                    </a>
                    
                    <a href="<?php echo base_url('index.php/Usuario/lista_usuarios'); ?>" 
                       class="btn btn-light report-btn">
                        <i class="fas fa-user me-2"></i>
                        <span>Lista de Usuarios</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.report-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.report-btn {
    border: 1px solid #e0e0e0;
    padding: 1rem;
    text-align: left;
    transition: all 0.2s ease;
}

.report-btn:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
    transform: translateY(-2px);
}

.report-btn i {
    color: #0d6efd;
}

h6 {
    font-size: 1.1rem;
    color: #333;
}
</style>
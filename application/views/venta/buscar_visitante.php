<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Buscar Visitante</h2>
                
                <!-- Formulario de búsqueda -->
                <?php echo form_open('venta/buscar_visitante', 'method="post" class="mb-4"'); ?>
                <div class="row align-items-end">
                    <div class="col-md">
                        <label for="termino" class="form-label">Buscar Visitante</label>
                        <input type="text" class="form-control" id="termino" name="termino" placeholder="Ingrese CI/NIT, nombre o apellido" value="<?php echo set_value('termino'); ?>">
                    </div>
                    <div class="col-md-auto mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                        <a href="<?php echo site_url('/visitante/agregar'); ?>" class="btn btn-success ms-2">Agregar nuevo visitante</a>
                    </div>
                </div>
                <?php echo form_close(); ?>

                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
                <?php endif; ?>

                <!-- Resultados de la búsqueda -->
                <?php if (isset($visitantes) && !empty($visitantes)): ?>
                    <h3 class="mt-4">Resultados de la búsqueda:</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>CI/NIT</th>
                                <th>Celular</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visitantes as $visitante): ?>
                                <tr>
                                    <td><?php echo $visitante['Nombre']; ?></td>
                                    <td><?php echo $visitante['PrimerApellido'] . ' ' . $visitante['SegundoApellido']; ?></td>
                                    <td><?php echo $visitante['CiNit']; ?></td>
                                    <td><?php echo $visitante['NroCelular']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary seleccionar-visitante" 
                                                data-id="<?php echo $visitante['idVisitante']; ?>"
                                                data-nombre="<?php echo $visitante['Nombre']; ?>"
                                                data-apellido="<?php echo $visitante['PrimerApellido'] . ' ' . $visitante['SegundoApellido']; ?>"
                                                data-cinit="<?php echo $visitante['CiNit']; ?>">
                                            Seleccionar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php elseif (isset($visitantes)): ?>
                    <div class="alert alert-info mt-3">
                        <p class="mb-0">No se encontró ningún visitante con esos datos.</p>
                    </div>
                <?php endif; ?>

                <!-- Formulario de Nueva Venta (inicialmente oculto) -->
                <div id="formulario-venta" style="display: none;">
        <h3 class="mt-4">Nueva Venta</h3>
        <?php echo form_open('venta/procesar_venta', 'id="venta-form"'); ?>
        <input type="hidden" name="idVisitante" id="idVisitante">
        <input type="hidden" name="idHorarios" id="idHorarios" value="<?php echo $horarios[0]['idHorarios']; ?>">
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="info-venta">
                    <p class="mb-0">
                        <strong>Visitante:</strong> <span id="nombre-visitante"></span>
                        <strong class="ms-3">CI/NIT:</strong> <span id="cinit-visitante"></span>
                    </p>
                    <p class="mb-0 mt-2">
                        <strong>Fecha:</strong> <?php echo date('l d/m/Y', strtotime('2024-10-25')); ?>
                    </p>
                    <p class="mb-0 mt-2">
                        <strong>Horario:</strong> 
                        Entrada: <?php echo $horarios[0]['HoraEntrada']; ?> / 
                        Cierre: <?php echo $horarios[0]['HoraCierre']; ?>
                        <span class="badge bg-success ms-2">
                            <?php echo ($horarios[0]['MaxVisitantes'] - $horarios[0]['tickets_vendidos']); ?> lugares disponibles
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Resto del formulario sin cambios -->
        <div class="mb-3">
            <label for="CantAdultoMayor" class="form-label">Cantidad Adulto Mayor <span class="text-danger">*</span></label>
            <input type="number" class="form-control cantidad" name="CantAdultoMayor" id="CantAdultoMayor" value="0" min="0" required>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label for="CantAdulto" class="form-label">Cantidad Adulto <span class="text-danger">*</span></label>
            <input type="number" class="form-control cantidad" name="CantAdulto" id="CantAdulto" value="0" min="0" required>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label for="CantInfante" class="form-label">Cantidad Infante <span class="text-danger">*</span></label>
            <input type="number" class="form-control cantidad" name="CantInfante" id="CantInfante" value="0" min="0" required>
            <div class="invalid-feedback">Este campo es obligatorio.</div>
        </div>

        <div class="mb-3">
            <label for="Comentario" class="form-label">Comentario</label>
            <textarea class="form-control" name="Comentario" rows="3"></textarea>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h4 class="mb-0">Total: <span id="total-venta">0.00</span> Bs.</h4>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Realizar Venta</button>
            <button type="button" class="btn btn-danger" id="cancelar-venta">Cancelar Venta</button>
        </div>
        
        <?php echo form_close(); ?>
    </div>
</div>

<style>
    .info-venta {
        font-size: 1.1rem;
    }
    
    .info-venta strong {
        color: #495057;
    }
    
    .card {
        border: 1px solid rgba(0,0,0,.125);
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .gap-2 {
        gap: 0.5rem!important;
    }
    
    .ms-2 {
        margin-left: 0.5rem!important;
    }
    
    .ms-3 {
        margin-left: 1rem!important;
    }
    
    .mt-2 {
        margin-top: 0.5rem!important;
    }
    
    .badge {
        font-size: 0.875rem;
        padding: 0.5em 0.75em;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var seleccionarBotones = document.querySelectorAll('.seleccionar-visitante');
    var formularioVenta = document.getElementById('formulario-venta');
    var cancelarVentaBoton = document.getElementById('cancelar-venta');
    var cantidadInputs = document.querySelectorAll('.cantidad');
    var totalSpan = document.getElementById('total-venta');
    var precios = <?php echo json_encode($precios); ?>;
    var disponiblesHorario = <?php echo $horarios[0]['MaxVisitantes'] - $horarios[0]['tickets_vendidos']; ?>;
    
    seleccionarBotones.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var idVisitante = this.getAttribute('data-id');
            var nombre = this.getAttribute('data-nombre');
            var apellido = this.getAttribute('data-apellido');
            var cinit = this.getAttribute('data-cinit');
            
            document.getElementById('idVisitante').value = idVisitante;
            document.getElementById('nombre-visitante').textContent = nombre + ' ' + apellido;
            document.getElementById('cinit-visitante').textContent = cinit;
            
            formularioVenta.style.display = 'block';
            formularioVenta.scrollIntoView({behavior: 'smooth'});
        });
    });

    // Función para cancelar la venta
    cancelarVentaBoton.addEventListener('click', function() {
        if (confirm('¿Está seguro que desea cancelar la venta?')) {
            formularioVenta.style.display = 'none';
            document.getElementById('venta-form').reset();
        }
    });

    // Función para calcular el total y verificar disponibilidad
    function calcularTotalYVerificarDisponibilidad() {
        var total = 0;
        var cantidadTotal = 0;
        cantidadInputs.forEach(function(input) {
            var tipo = input.name.replace('Cant', '').toLowerCase();
            var precio = precios.find(p => p.tipo.toLowerCase() === tipo);
            if (precio) {
                total += input.value * precio.precio;
            }
            cantidadTotal += parseInt(input.value);
        });
        totalSpan.textContent = total.toFixed(2);

        // Verificar disponibilidad
        if (cantidadTotal > disponiblesHorario) {
            alert('La cantidad total de tickets excede los lugares disponibles para este horario.');
            // Resetear las cantidades
            cantidadInputs.forEach(input => input.value = 0);
            calcularTotalYVerificarDisponibilidad(); // Recalcular
        }
    }

    // Eventos para los cambios en las cantidades
    cantidadInputs.forEach(function(input) {
        input.addEventListener('change', calcularTotalYVerificarDisponibilidad);
    });

    // Calcular total inicial
    calcularTotalYVerificarDisponibilidad();
});
</script>
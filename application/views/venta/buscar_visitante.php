<!-- Contenido de buscar_visitante.php -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Buscar Visitante</h2>
                
                <!-- Formulario de búsqueda -->
                <?php echo form_open('venta/buscar_visitante', 'method="post"'); ?>
                <div class="mb-3">
                    <label for="termino" class="form-label">Buscar Visitante</label>
                    <input type="text" class="form-control" id="termino" name="termino" placeholder="Ingrese CI/NIT, nombre o apellido" value="<?php echo set_value('termino'); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
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
                <?php else: ?>
                    <!-- Botón para agregar nuevo visitante si no se encuentra -->
                    <div class="mt-3">
                        <p>No se encontró ningún visitante con esos datos.</p>
                        <a href="<?php echo site_url('/visitante/agregar'); ?>" class="btn btn-success">Agregar nuevo visitante</a>
                    </div>
                <?php endif; ?>

                <!-- Formulario de Nueva Venta (inicialmente oculto) -->
                <div id="formulario-venta" style="display: none;">
                    <h3 class="mt-4">Nueva Venta</h3>
                    <?php echo form_open('venta/procesar_venta', 'id="venta-form"'); ?>
                    <input type="hidden" name="idVisitante" id="idVisitante">
                    
                    <div class="mb-3">
                        <p><strong>Visitante:</strong> <span id="nombre-visitante"></span></p>
                        <p><strong>CI/NIT:</strong> <span id="cinit-visitante"></span></p>
                    </div>

                    <div class="mb-3">
                        <label for="idHorarios" class="form-label">Horario <span class="text-danger">*</span></label>
                        <select class="form-control" name="idHorarios" id="idHorarios" required>
                            <option value="">Seleccione un horario</option>
                            <?php foreach ($horarios as $horario): ?>
                                <?php
                                $disponibles = $horario['MaxVisitantes'] - $horario['tickets_vendidos'];
                                ?>
                                <option value="<?php echo $horario['idHorarios']; ?>" data-disponibles="<?php echo $disponibles; ?>">
                                    <?php echo date('d/m/Y', strtotime($horario['Dia'])) . " - Entrada: " . $horario['HoraEntrada'] . " / Cierre: " . $horario['HoraCierre'] . " (Disponibles: " . $disponibles . ")"; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>

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

                    <div class="mb-3">
                        <h4>Total: <span id="total-venta">0.00</span> Bs.</h4>
                    </div>

                    <button type="submit" class="btn btn-primary">Realizar Venta</button>
                    <button type="button" class="btn btn-danger" id="cancelar-venta">Cancelar Venta</button>
                    
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .form-control.is-invalid {
        border-color: #dc3545;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var seleccionarBotones = document.querySelectorAll('.seleccionar-visitante');
    var formularioVenta = document.getElementById('formulario-venta');
    var cancelarVentaBoton = document.getElementById('cancelar-venta');
    var horariosSelect = document.getElementById('idHorarios');
    var cantidadInputs = document.querySelectorAll('.cantidad');
    var totalSpan = document.getElementById('total-venta');
    var precios = <?php echo json_encode($precios); ?>;
    
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
        var selectedOption = horariosSelect.options[horariosSelect.selectedIndex];
        if (selectedOption) {
            var disponibles = parseInt(selectedOption.getAttribute('data-disponibles'));
            if (cantidadTotal > disponibles) {
                alert('La cantidad total de tickets excede los lugares disponibles para este horario.');
                // Resetear las cantidades
                cantidadInputs.forEach(input => input.value = 0);
                calcularTotalYVerificarDisponibilidad(); // Recalcular
            }
        }
    }

    // Evento para el cambio de horario
    horariosSelect.addEventListener('change', calcularTotalYVerificarDisponibilidad);

    // Eventos para los cambios en las cantidades
    cantidadInputs.forEach(function(input) {
        input.addEventListener('change', calcularTotalYVerificarDisponibilidad);
    });

    // Calcular total inicial
    calcularTotalYVerificarDisponibilidad();
});
</script>
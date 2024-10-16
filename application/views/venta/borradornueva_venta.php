<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Nueva Venta</h2>
                
                <!-- Formulario de búsqueda -->
                <?php echo form_open('venta/buscar_visitante', 'method="post"'); ?>
                <div class="mb-3">
                    <label for="termino" class="form-label">Buscar Visitante</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="termino" name="termino" placeholder="Ingrese CI/NIT, nombre o apellido" value="<?php echo set_value('termino'); ?>">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </div>
                <?php echo form_close(); ?>

                <!-- Mostrar mensaje de error o éxito -->
                <?php if (isset($mensaje)): ?>
                    <div class="alert alert-info mt-3"><?php echo $mensaje; ?></div>
                <?php endif; ?>

                <!-- Mostrar resultados de la búsqueda -->
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
                                        <a href="<?php echo base_url('/index.php/venta/nueva_venta/' . $visitante['idVisitante']); ?>" class="btn btn-sm btn-primary">Seleccionar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <!-- Formulario de venta (visible solo si se ha seleccionado un visitante) -->
                <?php if (isset($visitante_seleccionado)): ?>
                    <h3 class="mt-4">Detalles de la Venta</h3>
                    <?php echo form_open('venta/procesar_venta', 'id="venta-form"'); ?>
                    <input type="hidden" name="idVisitante" value="<?php echo $visitante_seleccionado['idVisitante']; ?>">
                    
                    <div class="mb-3">
                        <p><strong>Visitante:</strong> <?php echo $visitante_seleccionado['Nombre'] . ' ' . $visitante_seleccionado['PrimerApellido'] . ' ' . $visitante_seleccionado['SegundoApellido']; ?></p>
                        <p><strong>CI/NIT:</strong> <?php echo $visitante_seleccionado['CiNit']; ?></p>
                    </div>

                    <div class="mb-3">
                        <label for="idHorarios" class="form-label">Horario</label>
                        <select class="form-control" name="idHorarios" required>
                            <option value="">Seleccione un horario</option>
                            <?php foreach ($horarios as $horario): ?>
                                <option value="<?php echo $horario['idHorarios']; ?>">
                                    <?php echo $horario['Dia'] . " - " . $horario['HoraEntrada'] . " / " . $horario['HoraCierre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="CantAdultoMayor" class="form-label">Cantidad Adulto Mayor</label>
                        <input type="number" class="form-control cantidad" name="CantAdultoMayor" value="0" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="CantAdulto" class="form-label">Cantidad Adulto</label>
                        <input type="number" class="form-control cantidad" name="CantAdulto" value="0" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="CantInfante" class="form-label">Cantidad Infante</label>
                        <input type="number" class="form-control cantidad" name="CantInfante" value="0" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="Comentario" class="form-label">Comentario</label>
                        <textarea class="form-control" name="Comentario" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <h4>Total: <span id="total-venta">0.00</span> Bs.</h4>
                    </div>

                    <button type="submit" class="btn btn-primary">Realizar Venta</button>
                    
                    <?php echo form_close(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cálculo del total
    var cantidadInputs = document.querySelectorAll('.cantidad');
    var totalSpan = document.getElementById('total-venta');
    var precios = <?php echo json_encode($precios); ?>;

    function calcularTotal() {
        var total = 0;
        cantidadInputs.forEach(function(input) {
            var tipo = input.name.replace('Cant', '').toLowerCase();
            var precio = precios.find(p => p.tipo.toLowerCase() === tipo);
            if (precio) {
                total += input.value * precio.precio;
            }
        });
        totalSpan.textContent = total.toFixed(2);
    }

    cantidadInputs.forEach(function(input) {
        input.addEventListener('change', calcularTotal);
    });

    calcularTotal(); // Calcular total inicial
});
</script>
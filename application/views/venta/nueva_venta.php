<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
<div class="container mt-4">
    <h2>Nueva Venta</h2>

    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <form id="ventaForm" action="<?php echo base_url('venta/procesar_venta'); ?>" method="POST">
        <!-- Sección de búsqueda de cliente -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Cliente</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="<?php echo base_url('/venta/agregar_visitante'); ?>" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Agregar Cliente
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <input type="text" id="buscarCliente" class="form-control" placeholder="Buscar cliente por CI o nombre...">
                    <input type="hidden" id="idVisitante" name="id_visitante" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div id="resultadosCliente" class="mt-2"></div>
            </div>
        </div>

        <!-- Sección de horario -->
        <!-- En nueva_venta.php, reemplazar la sección del select de horarios por: -->

<div class="card mb-4">
    <div class="card-header">
        <h5>Horario</h5>
    </div>
    <div class="card-body">
        <?php
        $horario_actual = null;
        $dia_actual = date('N'); // 1 (lunes) a 7 (domingo)
        
        foreach($horarios as $horario) {
            if($horario['DiaSemana'] == $dia_actual) {
                $horario_actual = $horario;
                break;
            }
        }
        
        if($horario_actual): 
            // Formatear la fecha actual
            $fecha = new DateTime();
            $dias_semana = [
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado',
                7 => 'Domingo'
            ];
            
            $fecha_formateada = $dias_semana[$fecha->format('N')] . ' ' . 
                               $fecha->format('d/m/Y H:i:s');
        ?>
            <input type="hidden" name="id_horario" value="<?php echo $horario_actual['idHorarios']; ?>">
            
            <div class="row">
                <div class="col-12">
                    <p class="mb-2">
                        <strong>Fecha:</strong> <?php echo $fecha_formateada; ?>
                    </p>
                    <p class="mb-2">
                        <strong>Horario:</strong> 
                        Entrada: <?php echo date('H:i:s', strtotime($horario_actual['HoraEntrada'])); ?> / 
                        Cierre: <?php echo date('H:i:s', strtotime($horario_actual['HoraCierre'])); ?>
                    </p>
                    <div class="mt-2">
                        <span class="badge bg-success" style="font-size: 1em; padding: 8px 15px;">
                            <?php echo $horario_actual['tickets_disponibles']; ?> tickets disponibles
                        </span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No hay horario disponible para hoy.
            </div>
        <?php endif; ?>
    </div>
</div>

        <!-- Sección de tickets -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Tickets</h5>
            </div>
            <div class="card-body">
                <table class="table" id="tablaTickets">
                    <thead>
                        <tr>
                            <th>Tipo de Ticket</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    
<!-- Modificar el template de la fila para quitar required inicial -->
<tr id="filaTemplate" style="display:none;">
    <td>
        <select name="tickets[]" class="form-control ticket-select">
            <option value="">Seleccione ticket</option>
            <?php foreach($tickets as $ticket): ?>
                <option value="<?php echo $ticket['idTickets']; ?>"
                        data-precio="<?php echo $ticket['precio']; ?>"
                        data-descripcion="<?php echo htmlspecialchars($ticket['descripcion']); ?>">
                    <?php echo $ticket['tipo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small class="text-muted descripcion-ticket"></small>
    </td>
    <td>
        <input type="number" name="cantidades[]" class="form-control cantidad" value="1" min="1">
    </td>
    <td class="precio">0.00</td>
    <td class="subtotal">0.00</td>
    <td>
        <button type="button" class="btn btn-danger btn-sm eliminar-fila">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>

                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" id="agregarFila">
                    <i class="fas fa-plus"></i> Agregar Ticket
                </button>
            </div>
        </div>

        <!-- Total y botones -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Total: Bs. <span id="total">0.00</span></h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Procesar Venta
                        </button>
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


<script>
    var BASE_URL = '<?php echo base_url(); ?>';
</script>

<!-- Luego incluimos jQuery si no está ya incluido -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Variable para controlar si ya se agregó la primera fila
    var primeraFilaAgregada = false;

    // Búsqueda de clientes
    $('#buscarCliente').on('keyup', function() {
        var termino = $(this).val();
        if(termino.length < 2) {
            $('#resultadosCliente').empty();
            return;
        }

        $.ajax({
            url: BASE_URL + 'venta/buscar_visitante_ajax',
            method: 'POST',
            data: { termino: termino },
            success: function(response) {
                var visitantes = JSON.parse(response);
                var html = '';
                visitantes.forEach(function(visitante) {
                    html += `<div class="cliente-resultado" data-id="${visitante.idVisitante}">
                                ${visitante.Nombre} ${visitante.PrimerApellido} - ${visitante.CiNit}
                            </div>`;
                });
                $('#resultadosCliente').html(html);
            }
        });
    });

    // Selección de cliente
    $(document).on('click', '.cliente-resultado', function() {
        var id = $(this).data('id');
        var nombre = $(this).text();
        $('#idVisitante').val(id);
        $('#buscarCliente').val(nombre);
        $('#resultadosCliente').empty();
    });

    // Función para agregar nueva fila
    function agregarNuevaFila() {
        var nuevaFila = $('#filaTemplate').clone();
        nuevaFila.removeAttr('id').show();
        
        // Modificar los nombres de los campos para que sean únicos
        var timestamp = new Date().getTime();
        nuevaFila.find('.ticket-select').attr('name', 'tickets[' + timestamp + ']')
                                      .prop('required', true);
        nuevaFila.find('.cantidad').attr('name', 'cantidades[' + timestamp + ']')
                                  .prop('required', true);
        
        $('#tablaTickets tbody').append(nuevaFila);
        actualizarTotal();
    }

    // Evento click para agregar fila
    $('#agregarFila').click(function(e) {
        e.preventDefault();
        agregarNuevaFila();
    });

    // Eliminar fila de ticket
    $(document).on('click', '.eliminar-fila', function() {
        if($('#tablaTickets tbody tr:visible').length > 1) {
            $(this).closest('tr').remove();
            actualizarTotal();
        } else {
            alert('Debe haber al menos un ticket en la venta');
        }
    });

    // Actualizar precios y descripción cuando cambia el ticket seleccionado
    $(document).on('change', '.ticket-select', function() {
        var fila = $(this).closest('tr');
        var opcionSeleccionada = $(this).find(':selected');
        
        // Asegurarse de que el precio se obtenga como número
        var precio = parseFloat(opcionSeleccionada.data('precio')) || 0;
        
        // Actualizar el precio en la fila con el formato correcto
        fila.find('.precio').text(precio.toFixed(2));
        
        // Actualizar la descripción
        var descripcion = opcionSeleccionada.data('descripcion') || '';
        fila.find('.descripcion-ticket').text(descripcion);
        
        // Actualizar subtotal
        actualizarSubtotal(fila);
        verificarDisponibilidad();
    });

    // Actualizar subtotal cuando cambia la cantidad
    $(document).on('change', '.cantidad', function() {
        var fila = $(this).closest('tr');
        actualizarSubtotal(fila);
        verificarDisponibilidad();
    });

    // Verificar disponibilidad cuando cambia horario
    $('select[name="id_horario"]').on('change', function() {
        verificarDisponibilidad();
    });

    // Función para verificar disponibilidad
    function verificarDisponibilidad() {
        var idHorario = $('select[name="id_horario"]').val();
        if (!idHorario) return;

        var tickets = [];
        $('#tablaTickets tbody tr:visible').each(function() {
            var idTicket = $(this).find('.ticket-select').val();
            var cantidad = parseInt($(this).find('.cantidad').val()) || 0;
            
            if (idTicket && cantidad > 0) {
                tickets.push({
                    id_ticket: idTicket,
                    cantidad: cantidad
                });
            }
        });

        if (tickets.length === 0) return;

        $.ajax({
            url: BASE_URL + 'venta/verificar_disponibilidad_ajax',
            method: 'POST',
            data: {
                id_horario: idHorario,
                tickets: tickets
            },
            success: function(response) {
                var resultado = JSON.parse(response);
                if(!resultado.disponible) {
                    alert('No hay disponibilidad suficiente para este horario. Capacidad disponible: ' + 
                          resultado.capacidad_disponible);
                    // Resetear cantidades a 1
                    $('.cantidad').val(1);
                    actualizarTotal();
                }
            }
        });
    }

    // Función para actualizar subtotal de una fila
    function actualizarSubtotal(fila) {
        var precio = parseFloat(fila.find('.precio').text()) || 0;
        var cantidad = parseInt(fila.find('.cantidad').val()) || 0;
        var subtotal = precio * cantidad;
        fila.find('.subtotal').text(subtotal.toFixed(2));
        actualizarTotal();
    }

    // Función para actualizar el total general
    function actualizarTotal() {
        var total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).text()) || 0;
        });
        $('#total').text(total.toFixed(2));
    }

    // Validación del formulario
    $('#ventaForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validar cliente
        if(!$('#idVisitante').val()) {
            alert('Debe seleccionar un cliente');
            return false;
        }

        // Validar horario
       /* if(!$('select[name="id_horario"]').val()) {
            alert('Debe seleccionar un horario');
            return false;
        }*/

        // Validar tickets
        var filasVisibles = $('#tablaTickets tbody tr:visible');
        
        if(filasVisibles.length < 1) {
            alert('Debe agregar al menos un ticket');
            return false;
        }

        var formValido = true;
        var datosVenta = [];

        filasVisibles.each(function() {
            var ticketSelect = $(this).find('.ticket-select');
            var cantidadInput = $(this).find('.cantidad');
            
            if(!ticketSelect.val()) {
                alert('Por favor seleccione un tipo de ticket');
                formValido = false;
                return false;
            }

            if(!cantidadInput.val() || cantidadInput.val() < 1) {
                alert('Por favor ingrese una cantidad válida');
                formValido = false;
                return false;
            }

            datosVenta.push({
                ticket_id: ticketSelect.val(),
                cantidad: cantidadInput.val()
            });
        });

        if(!formValido) {
            return false;
        }

        // Si todo está válido, enviar el formulario
        this.submit();
    });

    // Agregar primera fila al cargar la página
    if (!primeraFilaAgregada) {
        agregarNuevaFila();
        primeraFilaAgregada = true;
    }
});
</script>
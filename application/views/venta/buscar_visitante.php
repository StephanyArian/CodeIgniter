<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Buscar Visitante</h2>
                
                <!-- Formulario de búsqueda con autocompletado -->
                <div class="row align-items-end">
                    <div class="col-md position-relative">
                        <label for="termino" class="form-label">Buscar Visitante</label>
                        <input type="text" 
                               class="form-control" 
                               id="termino" 
                               name="termino" 
                               placeholder="Ingrese CI/NIT, nombre o apellido"
                               autocomplete="off">
                        <div id="resultados-busqueda" class="autocomplete-results"></div>
                    </div>
                    <div class="col-md-auto mt-3 mt-md-0">
                        <a href="<?php echo site_url('/venta/agregar_visitante'); ?>" class="btn btn-success ms-2">Agregar nuevo visitante</a>
                    </div>
                </div>

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
                                    <strong>Fecha:</strong> <?php echo date('l d/m/Y'); ?>
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
        </div>
    </div>
</div>

<style>
    .autocomplete-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: none;
    }

    .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s;
    }

    .autocomplete-item:hover {
        background-color: #f8f9fa;
    }

    .autocomplete-item .nombre {
        font-weight: 500;
        color: #212529;
    }

    .autocomplete-item .datos {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .highlight {
        background-color: #fff3cd;
        padding: 0 2px;
    }

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
    const baseUrl = '<?php echo base_url(); ?>';
    const inputBusqueda = document.getElementById('termino');
    const resultadosDiv = document.getElementById('resultados-busqueda');
    const formularioVenta = document.getElementById('formulario-venta');
    const cancelarVentaBoton = document.getElementById('cancelar-venta');
    const cantidadInputs = document.querySelectorAll('.cantidad');
    const totalSpan = document.getElementById('total-venta');
    const precios = <?php echo json_encode($precios); ?>;
    const disponiblesHorario = <?php echo $horarios[0]['MaxVisitantes'] - $horarios[0]['tickets_vendidos']; ?>;
    let timeoutId;

    // Función para escapar caracteres especiales en el texto de búsqueda
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Función para resaltar el texto coincidente
    function resaltarCoincidencias(texto, busqueda) {
        if (!busqueda) return texto;
        const regex = new RegExp(`(${escapeRegExp(busqueda)})`, 'gi');
        return texto.replace(regex, '<span class="highlight">$1</span>');
    }

    // Función para renderizar los resultados
    function mostrarResultados(visitantes, terminoBusqueda) {
        if (visitantes.length === 0) {
            resultadosDiv.innerHTML = '<div class="autocomplete-item">No se encontraron resultados</div>';
            resultadosDiv.style.display = 'block';
            return;
        }

        const html = visitantes.map(visitante => `
            <div class="autocomplete-item" 
                 data-id="${visitante.idVisitante}"
                 data-nombre="${visitante.Nombre}"
                 data-apellido="${visitante.PrimerApellido} ${visitante.SegundoApellido}"
                 data-cinit="${visitante.CiNit}">
                <div class="nombre">
                    ${resaltarCoincidencias(visitante.Nombre + ' ' + visitante.PrimerApellido + ' ' + visitante.SegundoApellido, terminoBusqueda)}
                </div>
                <div class="datos">
                    CI/NIT: ${resaltarCoincidencias(visitante.CiNit, terminoBusqueda)} | 
                    Cel: ${resaltarCoincidencias(visitante.NroCelular || '-', terminoBusqueda)}
                </div>
            </div>
        `).join('');

        resultadosDiv.innerHTML = html;
        resultadosDiv.style.display = 'block';
    }

    // Función para realizar la búsqueda
    function realizarBusqueda(termino) {
        if (termino.length < 2) {
            resultadosDiv.style.display = 'none';
            return;
        }

        fetch(`${baseUrl}venta/buscar_visitante_ajax`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `termino=${encodeURIComponent(termino)}`
        })
        .then(response => response.json())
        .then(data => {
            mostrarResultados(data, termino);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Event listener para el input de búsqueda
    inputBusqueda.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            realizarBusqueda(this.value.trim());
        }, 300);
    });

    // Event listener para seleccionar un visitante
    resultadosDiv.addEventListener('click', function(e) {
        const item = e.target.closest('.autocomplete-item');
        if (!item) return;

        const idVisitante = item.dataset.id;
        const nombre = item.dataset.nombre;
        const apellido = item.dataset.apellido;
        const cinit = item.dataset.cinit;

        document.getElementById('idVisitante').value = idVisitante;
        document.getElementById('nombre-visitante').textContent = nombre + ' ' + apellido;
        document.getElementById('cinit-visitante').textContent = cinit;

        formularioVenta.style.display = 'block';
        formularioVenta.scrollIntoView({behavior: 'smooth'});
        
        // Limpiar búsqueda
        inputBusqueda.value = '';
        resultadosDiv.style.display = 'none';
    });

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!inputBusqueda.contains(e.target) && !resultadosDiv.contains(e.target)) {
            resultadosDiv.style.display = 'none';
        }
    });

    // Función para cancelar la venta
    cancelarVentaBoton.addEventListener('click', function() {
        if (confirm('¿Está seguro que desea cancelar la venta?')) {
            formularioVenta.style.display = 'none';
            document.getElementById('venta-form').reset();
            document.getElementById('termino').value = '';
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
        input.addEventListener('input', calcularTotalYVerificarDisponibilidad);
    });

    // Calcular total inicial
    calcularTotalYVerificarDisponibilidad();
});
</script>
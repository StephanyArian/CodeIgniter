<style>
    .table th {
        white-space: nowrap;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .badge {
        padding: 0.5em 0.8em;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .gap-2 {
        gap: 0.5rem!important;
    }
    .me-1 {
        margin-right: 0.25rem!important;
    }
    .me-2 {
        margin-right: 0.5rem!important;
    }
    .user-photo {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Horarios Disponibles</h2>
                    
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;" class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                            </div>
                                        </th>
                                        <th>Día</th>
                                        <th>Hora de Entrada</th>
                                        <th>Hora de Cierre</th>
                                        <th class="text-center">Capacidad Máxima</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horarios as $horario): ?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input row-checkbox" type="checkbox" 
                                                       value="<?php echo $horario['idHorarios']; ?>"
                                                       data-id="<?php echo $horario['idHorarios']; ?>"
                                                       <?php echo $horario['Estado'] ? 'checked' : ''; ?>>
                                            </div>
                                        </td>
                                        <td><?php echo $dias_semana[$horario['DiaSemana']]; ?></td>
                                        <td><?php echo $horario['HoraEntrada']; ?></td>
                                        <td><?php echo $horario['HoraCierre']; ?></td>
                                        <td class="text-center"><?php echo $horario['MaxVisitantes']; ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo $horario['Estado'] ? 'bg-success' : 'bg-danger'; ?> estado-texto" 
                                                  data-id="<?php echo $horario['idHorarios']; ?>">
                                                <?php echo $horario['Estado'] ? 'Abierto' : 'Cerrado'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo site_url('horario/editar/' . $horario['idHorarios']); ?>" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
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

<style>
.form-check {
    margin: 0;
    display: flex;
    justify-content: center;
}
.estado-texto {
    display: block;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            updateEstado(checkbox);
        });
    });

    // Estado checkbox functionality
    rowCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateEstado(this);
        });
    });

    function updateEstado(checkbox) {
        const horarioId = checkbox.dataset.id;
        const newEstado = checkbox.checked ? 1 : 0;
        const estadoTexto = document.querySelector(`.estado-texto[data-id="${horarioId}"]`);

        // Update the text immediately
        estadoTexto.textContent = newEstado ? 'Abierto' : 'Cerrado';

        // Send AJAX request to update estado
        fetch('<?php echo site_url("horario/actualizar_estado"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id: horarioId,
                estado: newEstado
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // If update failed, revert both the checkbox and text
                checkbox.checked = !checkbox.checked;
                estadoTexto.textContent = checkbox.checked ? 'Abierto' : 'Cerrado';
                alert('Error al actualizar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // If error occurs, revert both the checkbox and text
            checkbox.checked = !checkbox.checked;
            estadoTexto.textContent = checkbox.checked ? 'Abierto' : 'Cerrado';
            alert('Error al actualizar el estado');
        });
    }
});
</script>
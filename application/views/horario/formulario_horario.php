<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2><?php echo isset($horario) ? 'Editar Horario' : 'Agregar Horario'; ?></h2>
                
                <?php echo form_open(isset($horario) ? 'horario/editar/'.$horario['idHorarios'] : 'horario/agregar', ['id' => 'form-horario']); ?>
                
                <div class="mb-3">
                    <label for="DiaSemana" class="form-label">Día de la Semana <span class="text-danger">*</span></label>
                    <select class="form-control" name="DiaSemana" required>
                        <?php foreach ($dias_semana as $num => $dia): ?>
                            <option value="<?php echo $num; ?>" <?php echo (isset($horario) && $horario['DiaSemana'] == $num) ? 'selected' : ''; ?>>
                                <?php echo $dia; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="HoraEntrada" class="form-label">Hora de Entrada <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="HoraEntrada" value="<?php echo isset($horario) ? $horario['HoraEntrada'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="HoraCierre" class="form-label">Hora de Cierre <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="HoraCierre" value="<?php echo isset($horario) ? $horario['HoraCierre'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="MaxVisitantes" class="form-label">Capacidad Máxima <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="MaxVisitantes" value="<?php echo isset($horario) ? $horario['MaxVisitantes'] : ''; ?>" required min="1">
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="Estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select class="form-control" name="Estado" required>
                        <option value="1" <?php echo (isset($horario) && $horario['Estado'] == 1) ? 'selected' : ''; ?>>Abierto</option>
                        <option value="0" <?php echo (isset($horario) && $horario['Estado'] == 0) ? 'selected' : ''; ?>>Cerrado</option>
                    </select>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo isset($horario) ? 'Actualizar' : 'Agregar'; ?></button>
                
                <?php echo form_close(); ?>
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
    var form = document.getElementById('form-horario');
    
    form.addEventListener('submit', function(event) {
        var isValid = true;
        var requiredInputs = form.querySelectorAll('[required]');
        
        requiredInputs.forEach(function(input) {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    
    form.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
<!-- Inicio del contenido principal -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2><?php echo isset($visitante) ? 'Editar Visitante' : 'Agregar Visitante'; ?></h2>
                <?php if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
                
                <?php echo form_open(isset($visitante) ? 'visitante/editar/'.$visitante['idVisitante'] : 'visitante/agregar', array('id' => 'form-visitante')); ?>
                
                <div class="mb-3">
                    <label for="Nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Nombre" id="Nombre" value="<?php echo isset($visitante) ? $visitante['Nombre'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="PrimerApellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="PrimerApellido" id="PrimerApellido" value="<?php echo isset($visitante) ? $visitante['PrimerApellido'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" name="SegundoApellido" id="SegundoApellido" value="<?php echo isset($visitante) ? $visitante['SegundoApellido'] : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="CiNit" class="form-label">CI/NIT <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="CiNit" id="CiNit" value="<?php echo isset($visitante) ? $visitante['CiNit'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="NroCelular" class="form-label">Número Celular <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="NroCelular" id="NroCelular" value="<?php echo isset($visitante) ? $visitante['NroCelular'] : ''; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="Email" id="Email" value="<?php echo isset($visitante) ? $visitante['Email'] : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="Estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select class="form-control" name="Estado" id="Estado" required>
                        <option value="1" <?php echo isset($visitante) && $visitante['Estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo isset($visitante) && $visitante['Estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo isset($visitante) ? 'Actualizar' : 'Agregar'; ?></button>
                
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
    var form = document.getElementById('form-visitante');
    
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
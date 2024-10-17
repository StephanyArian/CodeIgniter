<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2><?php echo isset($precio) ? 'Editar Precio' : 'Agregar Nuevo Precio'; ?></h2>
                <?php echo validation_errors(); ?>
                <?php echo form_open(isset($precio) ? 'precios/editar/'.$precio['id'] : 'precios/agregar', array('id' => 'form-precio')); ?>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <?php foreach ($tipos_permitidos as $tipo): ?>
                                <option value="<?php echo $tipo; ?>" <?php echo (isset($precio) && $precio['tipo'] == $tipo) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst(str_replace('_', ' ', $tipo)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Por favor, seleccione un tipo.</div>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo isset($precio) ? $precio['precio'] : set_value('precio'); ?>" required>
                        <div class="invalid-feedback">Por favor, ingrese un precio válido.</div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo isset($precio) ? 'Actualizar' : 'Agregar'; ?></button>
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
    var form = document.getElementById('form-precio');
    
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
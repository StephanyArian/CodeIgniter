<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Registro Rápido de Visitante</h2>
                    <a href="<?php echo site_url('Venta/nueva_venta'); ?>" class="btn btn-secondary">Volver a Búsqueda</a>
                </div>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                <?php endif; ?>

                <?php echo form_open('venta/guardar_visitante', 'id="venta-visitante-form" class="needs-validation" novalidate'); ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="CiNit" class="form-label">CI/NIT <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="CiNit" 
                                   name="CiNit" 
                                   required>
                            <div class="invalid-feedback">CI/NIT es requerido</div>
                        </div>

                        <div class="col-md-6">
                            <label for="NroCelular" class="form-label">Celular</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="NroCelular" 
                                   name="NroCelular" 
                                   maxlength="8">
                        </div>

                        <div class="col-md-4">
                            <label for="Nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="Nombre" 
                                   name="Nombre" 
                                   required>
                            <div class="invalid-feedback">Nombre es requerido</div>
                        </div>

                        <div class="col-md-4">
                            <label for="PrimerApellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="PrimerApellido" 
                                   name="PrimerApellido" 
                                   required>
                            <div class="invalid-feedback">Primer apellido es requerido</div>
                        </div>

                        <div class="col-md-4">
                            <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="SegundoApellido" 
                                   name="SegundoApellido">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button  type="submit" class="btn btn-primary">Guardar y Continuar con la Venta</button>
                        <a href="<?php echo site_url('Venta/nueva_venta'); ?>" class="btn btn-outline-danger ms-2">Cancelar</a>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.text-danger {
    color: #dc3545;
}

.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875rem;
}

.was-validated .form-control:invalid ~ .invalid-feedback {
    display: block;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('venta-visitante-form');
    
    // Validación del formulario
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Validar CI/NIT (solo números y letras)
    const ciNitInput = document.getElementById('CiNit');
    ciNitInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9a-zA-Z]/g, '').toUpperCase();
    });

    // Validar celular (solo números, máximo 8 dígitos)
    const celularInput = document.getElementById('NroCelular');
    celularInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8);
    });

    // Validar campos de texto (solo letras, espacios y tildes)
    const textInputs = [
        document.getElementById('Nombre'),
        document.getElementById('PrimerApellido'),
        document.getElementById('SegundoApellido')
    ];

    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
            // Convertir primera letra de cada palabra a mayúscula
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
        });
    });
});
</script>
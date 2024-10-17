<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Agregar Usuario</h2>
                <br>
                <?php 
                echo form_open_multipart("usuario/agregarbd", ['id' => 'form-usuario']);
                ?>
                <div class="mb-3">
                    <label for="PrimerApellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="PrimerApellido" placeholder="Escribe el Primer Apellido" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="mb-3">
                    <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" name="SegundoApellido" placeholder="Escribe el Segundo Apellido">
                </div>
                <div class="mb-3">
                    <label for="Nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Nombres" placeholder="Escribe los Nombres" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="Email" placeholder="Escribe el Email" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="mb-3">
                    <label for="NombreUsuario" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="NombreUsuario" placeholder="Escribe el Nombre de Usuario" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="mb-3">
                    <label for="Clave" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="Clave" placeholder="Escribe la Contraseña" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="mb-3">
                    <label for="Rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select class="form-control" name="Rol" required>
                        <option value="admin" <?php echo isset($usuario) && $usuario['Rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="usuario" <?php echo isset($usuario) && $usuario['Rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    </select>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>

                <button type="submit" class="btn btn-success">Agregar</button>
                <a href="<?php echo site_url('usuario/enviar_email'); ?>" class="btn btn-primary">Enviar Correo</a>
                <?php 
                echo form_close();
                ?>
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
    var form = document.getElementById('form-usuario');
    
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
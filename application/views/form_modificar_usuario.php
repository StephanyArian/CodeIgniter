<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Modificar Usuario</h2>
                <?php echo form_open('usuario/modificarbd', ['id' => 'form-modificar-usuario']); ?>
                <input type="hidden" name="idUsuarios" value="<?php echo $infousuario->idUsuarios; ?>">
                <div class="form-group">
                    <label for="PrimerApellido">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="PrimerApellido" value="<?php echo $infousuario->PrimerApellido; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="form-group">
                    <label for="SegundoApellido">Segundo Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="SegundoApellido" value="<?php echo $infousuario->SegundoApellido; ?>" >
                </div>
                <div class="form-group">
                    <label for="Nombres">Nombres <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Nombres" value="<?php echo $infousuario->Nombres; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="form-group">
                    <label for="Email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="Email" value="<?php echo $infousuario->Email; ?>" >
                </div>
                <div class="form-group">
                    <label for="NombreUsuario">Nombre de Usuario <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="NombreUsuario" value="<?php echo $infousuario->NombreUsuario; ?>" required>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <div class="form-group">
                    <label for="Clave">Contraseña</label>
                    <input type="password" class="form-control" name="Clave" placeholder="Escribe Nueva Contraseña si desea modificarla">
                </div>
                <div class="form-group">
                    <label for="Rol" class="form-label">Rol <span class="text-danger">*</span></label>
                    <select class="form-control" name="Rol" required>
                        <option value="admin" <?php echo isset($usuario) && $usuario['Rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        <option value="usuario" <?php echo isset($usuario) && $usuario['Rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    </select>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
                <button type="submit" class="btn btn-primary">Modificar Usuario</button>
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
    var form = document.getElementById('form-modificar-usuario');
    
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
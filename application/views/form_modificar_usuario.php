

    <!-- Inicio del contenido principal -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
    <h2>Modificar Usuario</h2>
    <?php echo form_open('usuario/modificarbd'); ?>
        <input type="hidden" name="idUsuarios" value="<?php echo $infousuario->idUsuarios; ?>">
        <div class="form-group">
            <label for="PrimerApellido">Primer Apellido</label>
            <input type="text" class="form-control" name="PrimerApellido" value="<?php echo $infousuario->PrimerApellido; ?>" required>
        </div>
        <div class="form-group">
            <label for="SegundoApellido">Segundo Apellido</label>
            <input type="text" class="form-control" name="SegundoApellido" value="<?php echo $infousuario->SegundoApellido; ?>" required>
        </div>
        <div class="form-group">
            <label for="Nombres">Nombres</label>
            <input type="text" class="form-control" name="Nombres" value="<?php echo $infousuario->Nombres; ?>" required>
        </div>
        <div class="form-group">
            <label for="Email">Email</label>
            <input type="email" class="form-control" name="Email" value="<?php echo $infousuario->Email; ?>" required>
        </div>
        <div class="form-group">
            <label for="NombreUsuario">Nombre de Usuario</label>
            <input type="text" class="form-control" name="NombreUsuario" value="<?php echo $infousuario->NombreUsuario; ?>" required>
        </div>
        <div class="form-group">
            <label for="Clave">Contraseña</label>
            <input type="password" class="form-control" name="Clave" placeholder="Escribe Nueva Contraseña si desea modificarla">
        </div>
                   <div class="form-group">
                     <label for="Rol" class="form-label">Rol</label>
                     <select class="form-control" name="Rol">
                       <option value="admin" <?php echo isset($usuario) && $usuario['Rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                       <option value="usuario" <?php echo isset($usuario) && $usuario['Rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                     </select>
                    </div>
                   <button type="submit" class="btn btn-primary">Modificar Usuario</button>
                <?php echo form_close(); ?>
             </div>
            </div>
        </div>
    </div>
    
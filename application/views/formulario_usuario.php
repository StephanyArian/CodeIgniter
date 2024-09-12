

    <!-- Inicio del contenido principal -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <h2>Agregar Usuario</h2>
                    <br>
                    <?php 
                    echo form_open_multipart("usuario/agregarbd");
                    ?>
                    <input type="text" class="form-control mb-3" name="PrimerApellido" placeholder="Escribe el Primer Apellido" required>
                    <input type="text" class="form-control mb-3" name="SegundoApellido" placeholder="Escribe el Segundo Apellido">
                    <input type="text" class="form-control mb-3" name="Nombres" placeholder="Escribe los Nombres" required>
                    <input type="email" class="form-control mb-3" name="Email" placeholder="Escribe el Email" required>
                    <input type="text" class="form-control mb-3" name="NombreUsuario" placeholder="Escribe el Nombre de Usuario" required>
                    <input type="password" class="form-control mb-3" name="Clave" placeholder="Escribe la Contraseña" required>
                    <div class="mb-3">
                     <label for="Rol" class="form-label">Rol</label>
                     <select class="form-control" name="Rol">
                       <option value="admin" <?php echo isset($usuario) && $usuario['Rol'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                       <option value="usuario" <?php echo isset($usuario) && $usuario['Rol'] == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                     </select>
                    </div>

                    <button type="submit" class="btn btn-success">Agregar </button>
                    <a href="<?php echo site_url('usuario/enviar_email'); ?>" class="btn btn-primary">Enviar Correo</a>
                    <?php 
                    echo form_close();
                    ?>

                </div>
            </div>
        </div>
    </div>
    <!-- Fin del contenido principal -->

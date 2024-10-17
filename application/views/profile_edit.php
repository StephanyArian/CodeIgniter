<!-- application/views/profile_edit.php -->
<!-- Inicio del contenido principal -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Editar Perfil</h2>
                
                <?php echo form_open_multipart('usuario/actualizar_perfil'); ?>

                <!-- Campo para la foto de perfil -->
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto de Perfil</label>
                    <input type="file" class="form-control" id="foto" name="foto">
                    <?php if (!empty($usuario->Foto)): ?>
                        <img src="<?php echo base_url('uploads/usuarios/perfil.jpg'); ?>" alt="Foto de Perfil" class="img-thumbnail mt-3" width="100">
                    <?php else: ?>
                        <img src="<?php echo base_url('uploads/usuarios/' . $usuario->foto); ?>" alt="Foto de Perfil" class="img-thumbnail mt-3" width="100">
                    <?php endif; ?>
                </div>

                 <!-- Campo para el primer apellido -->
                 <div class="mb-3">
                    <label for="PrimerApellido" class="form-label">Primer Apellido</label>
                    <input type="text" class="form-control" id="PrimerApellido" name="PrimerApellido" value="<?php echo set_value('PrimerApellido', $usuario->PrimerApellido); ?>">
                </div>

                 <!-- Campo para el segundo apellido -->
                 <div class="mb-3">
                    <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
                    <input type="text" class="form-control" id="SegundoApellido" name="SegundoApellido" value="<?php echo set_value('SegundoApellido', $usuario->SegundoApellido); ?>">
                </div>

                <!-- Campo para el nombre -->
                <div class="mb-3">
                    <label for="Nombres" class="form-label">Nombres</label>
                    <input type="text" class="form-control" name="Nombres" value="<?php echo set_value('Nombres', $usuario->Nombres); ?>">
                </div>
                
                <!-- Campo para el nombre de usuario -->
                <div class="mb-3">
                    <label for="NombreUsuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="NombreUsuario" name="NombreUsuario" value="<?php echo set_value('NombreUsuario', $usuario->NombreUsuario); ?>">
                </div>

                <!-- Campo para el email -->
                <div class="mb-3">
                    <label for="Email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="Email" name="Email" value="<?php echo set_value('Email', $usuario->Email); ?>">
                </div>

                <!-- Campo para la contraseña -->
                <div class="mb-3">
                    <label for="Clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="Clave" name="Clave">
                    <small>Deja en blanco si no deseas cambiar la contraseña</small>
                </div>

                <!-- Campo oculto para el ID del usuario -->
                <input type="hidden" name="idUsuarios" value="<?php echo $usuario->idUsuarios; ?>">

                <!-- Botón para enviar el formulario -->
                <button type="submit" class="btn btn-primary">Actualizar</button>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

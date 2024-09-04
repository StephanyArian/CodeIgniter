<!-- Inicio del contenido principal -->
<div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">

                    <h2 >Lista de Usuarios</h2>
                    <a href="<?php echo base_url(); ?>index.php/usuario/agregar">
                        <button type="button" class="btn btn-primary mb-3">Agregar Usuario</button>
                    </a>

                    <br>
      <?php echo form_open_multipart('usuario/listapdf'); ?>
        <button type="submit" name="buton2" class="btn btn-success">Lista usuarios PDF</button>
      <?php echo form_close(); ?>
      

                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
		                        <th>Subir</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Nombres</th>
                                <th>Email</th>
                                <th>Nombre de Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 1;
                            foreach ($usuarios->result() as $usuario) {
                            ?>
                            <tr>
                                <td><?php echo $contador; ?></td>
                                <td>
			                      <?php
				                  $foto=$usuario->foto;

				                if($foto=="")
			                        {
			                    	?>
                                  <img src="<?php echo base_url(); ?>uploads/estudiantes/perfil.jpg" width="60">
			                     <?php
			                     }else{
			                     ?>
                                    <img src="<?php echo base_url(); ?>uploads/estudiantes/<?php echo $foto; ?>" width="60">
			                     <?php
			                     }
			                     ?>
			                    </td>
			                    <td>
				                     <?php
				                      	echo form_open_multipart("usuario/subirfoto");
				                     ?>
				                     <input type="hidden" name="idUsuarios" value="<?php echo $usuario->idUsuarios; ?>">
				                     <button type="submit" class="btn btn-primary">Subir</button>
			                         <?php 
				                    	echo form_close();
			                        	?>
			                    </td>
                                <td><?php echo $usuario->PrimerApellido; ?></td>
                                <td><?php echo $usuario->SegundoApellido; ?></td>
                                <td><?php echo $usuario->Nombres; ?></td>
                                <td><?php echo $usuario->Email; ?></td>
                                <td><?php echo $usuario->NombreUsuario; ?></td>
                                <td>
                                <a href="<?php echo base_url('index.php/usuario/modificar/' . $usuario->idUsuarios); ?>">
                                        <button type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                                    </a>
                                    
                                    <a href="<?php echo base_url('index.php/usuario/eliminarbd/' . $usuario->idUsuarios); ?>" onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                        <button type="button" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i></button>
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $contador++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
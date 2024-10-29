<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <!-- Header con título y botones -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Lista de Usuarios</h2>
                    <div class="d-flex gap-2">
                        <a href="<?php echo base_url(); ?>index.php/usuario/agregar" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Agregar Usuario
                        </a>
                        <?php echo form_open_multipart('usuario/listapdf'); ?>
                            <button type="submit" name="buton2" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Lista PDF
                            </button>
                        <?php echo form_close(); ?>
                    </div>
                </div>

                <?php if($this->session->flashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('mensaje'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Card con la tabla -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Foto</th>
                                        <th>Primer Apellido</th>
                                        <th>Segundo Apellido</th>
                                        <th>Nombres</th>
                                        <th>Email</th>
                                        <th>Nombre de Usuario</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contador = 1;
                                    foreach ($usuarios->result() as $usuario) {
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo $contador; ?></td>
                                        <td class="text-center">
                                            <?php
                                            $foto = $usuario->foto;
                                            $fotoUrl = empty($foto) ? 
                                                base_url().'uploads/usuarios/perfil.jpg' : 
                                                base_url().'uploads/usuarios/'.$foto;
                                            ?>
                                            <div class="position-relative">
                                                <img src="<?php echo $fotoUrl; ?>" class="rounded-circle user-photo" alt="Foto de perfil">
                                                <?php echo form_open_multipart("usuario/subirfoto", ['class' => 'upload-form']); ?>
                                                    <input type="hidden" name="idUsuarios" value="<?php echo $usuario->idUsuarios; ?>">
                                                    <button type="submit" class="btn btn-sm btn-light upload-btn" title="Subir foto">
                                                        <i class="fas fa-camera"></i>
                                                    </button>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </td>
                                        <td><?php echo $usuario->PrimerApellido; ?></td>
                                        <td><?php echo $usuario->SegundoApellido; ?></td>
                                        <td><?php echo $usuario->Nombres; ?></td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo $usuario->Email; ?>">
                                                <?php echo $usuario->Email; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $usuario->NombreUsuario; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url('index.php/usuario/modificar/' . $usuario->idUsuarios); ?>" 
                                               class="btn btn-sm btn-warning me-2" 
                                               title="Editar">
                                                <i class="fas fa-edit me-1"></i>Editar
                                            </a>
                                            <a href="<?php echo base_url('index.php/usuario/eliminarbd/' . $usuario->idUsuarios); ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('¿Está seguro de eliminar este usuario?');"
                                               title="Eliminar">
                                                <i class="fas fa-trash-alt me-1"></i>Eliminar
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
        </div>
    </div>
</div>

<style>
    .table th {
        white-space: nowrap;
        padding: 1rem;
    }

    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.25rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .user-photo {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }

    .position-relative {
        position: relative;
        display: inline-block;
    }

    .upload-form {
        position: absolute;
        bottom: -5px;
        right: -5px;
    }

    .upload-btn {
        width: 24px;
        height: 24px;
        padding: 0;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background-color: #fff;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .upload-btn:hover {
        background-color: #f8f9fa;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table-responsive {
        margin: -1rem;
    }

    .alert {
        margin-bottom: 1.5rem;
    }

    .gap-2 {
        gap: 0.5rem!important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.04);
    }
</style>
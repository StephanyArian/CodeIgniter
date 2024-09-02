<div class="content">
    <!-- Inicio de la barra de navegación -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
            <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
        </a>
        <a href="#" class="sidebar-toggler flex-shrink-0">
            <i class="fa fa-bars"></i>
        </a>
        <form class="d-none d-md-flex ms-4">
            <input class="form-control border-0" type="search" placeholder="Search">
        </form>
        <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-envelope me-lg-2"></i>
                    <span class="d-none d-lg-inline-flex">Message</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <div class="ms-2">
                                <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                <small>15 minutes ago</small>
                            </div>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item text-center">See all message</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fa fa-bell me-lg-2"></i>
                    <span class="d-none d-lg-inline-flex">Notification</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="#" class="dropdown-item">
                        <h6 class="fw-normal mb-0">Profile updated</h6>
                        <small>15 minutes ago</small>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <h6 class="fw-normal mb-0">New user added</h6>
                        <small>15 minutes ago</small>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item">
                        <h6 class="fw-normal mb-0">Password changed</h6>
                        <small>15 minutes ago</small>
                    </a>
                    <hr class="dropdown-divider">
                    <a href="#" class="dropdown-item text-center">See all notifications</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                    <span class="d-none d-lg-inline-flex">John Doe</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="#" class="dropdown-item">My Profile</a>
                    <a href="#" class="dropdown-item">Settings</a>
                    <a href="#" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Fin de la barra de navegación -->

    <!-- Inicio del contenido principal -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
<div class="bg-light rounded h-100 p-4">
    <h2><?php echo isset($visitante) ? 'Editar Visitante' : 'Agregar Visitante'; ?></h2>
    
    <?php echo form_open(isset($visitante) ? 'visitante/editar/'.$visitante['idVisitante'] : 'visitante/agregar'); ?>
    
    <div class="mb-3">
        <label for="Nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" name="Nombre" value="<?php echo isset($visitante) ? $visitante['Nombre'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="PrimerApellido" class="form-label">Primer Apellido</label>
        <input type="text" class="form-control" name="PrimerApellido" value="<?php echo isset($visitante) ? $visitante['PrimerApellido'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="SegundoApellido" class="form-label">Segundo Apellido</label>
        <input type="text" class="form-control" name="SegundoApellido" value="<?php echo isset($visitante) ? $visitante['SegundoApellido'] : ''; ?>">
    </div>

    <div class="mb-3">
        <label for="CiNit" class="form-label">CI/NIT</label>
        <input type="text" class="form-control" name="CiNit" value="<?php echo isset($visitante) ? $visitante['CiNit'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="NroCelular" class="form-label">Número Celular</label>
        <input type="text" class="form-control" name="NroCelular" value="<?php echo isset($visitante) ? $visitante['NroCelular'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="Email" class="form-label">Email</label>
        <input type="email" class="form-control" name="Email" value="<?php echo isset($visitante) ? $visitante['Email'] : ''; ?>" required>
    </div>

    <div class="mb-3">
        <label for="Estado" class="form-label">Estado</label>
        <select class="form-control" name="Estado">
            <option value="1" <?php echo isset($visitante) && $visitante['Estado'] == 1 ? 'selected' : ''; ?>>Activo</option>
            <option value="0" <?php echo isset($visitante) && $visitante['Estado'] == 0 ? 'selected' : ''; ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary"><?php echo isset($visitante) ? 'Actualizar' : 'Agregar'; ?></button>
    
    <?php echo form_close(); ?>
</div>
</div>
        </div>
    </div>
    <!-- Fin del contenido principal -->
</div>
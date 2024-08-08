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
        <button type="submit" class="btn btn-primary">Modificar Usuario</button>
    <?php echo form_close(); ?>
</div>
            </div>
        </div>
    </div>
    <!-- Fin del contenido principal -->
</div>
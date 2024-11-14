<!-- application/views/templates/sidebar.php -->
<?php
// Función para determinar si la página actual coincide con el enlace del menú
function is_active($page) {
    $ci =& get_instance();
    return ($ci->uri->segment(1) == $page);
}
?>
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="<?php echo base_url('index.php/Dashboard/index#'); ?>" class="nav-item nav-link <?php echo is_active('Dashboard') ? 'active' : ''; ?>" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>AGROFLORI</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <?php
                $foto = $this->session->userdata('Foto');
                $imgSrc = empty($foto) ? base_url('uploads/usuarios/perfil.jpg') : base_url('uploads/usuarios/' . $foto);
                ?>
                <img class="rounded-circle" src="<?php echo $imgSrc; ?>" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0"><?php echo $this->session->userdata('NombreUsuario'); ?></h6>
                <span><?php echo $this->session->userdata('Rol'); ?></span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="<?php echo base_url('index.php/Dashboard/index#'); ?>" class="nav-item nav-link <?php echo is_active('Dashboard') ? 'active' : ''; ?>"><i class="fa fa-tachometer-alt me-2"></i>Panel</a>
           
            <?php if($this->session->userdata('Rol') === 'admin'): ?>
            <a href="<?php echo base_url('index.php/Ticket/index#'); ?>" class="nav-item nav-link <?php echo is_active('Ticket') ? 'active' : ''; ?>"> <i class="fas fa-ticket-alt me-2"></i>Gestion Tickets</a>
            <?php endif; ?>
            
            <a href="<?php echo base_url('index.php/Venta/index#'); ?>" class="nav-item nav-link <?php echo is_active('Venta') ? 'active' : ''; ?>"><i class="fa fa-keyboard me-2"></i>Ventas</a>
            <a href="<?php echo base_url('index.php/Horario/index#'); ?>" class="nav-item nav-link <?php echo is_active('Horario') ? 'active' : ''; ?>"><i class="far fa-clock me-2"></i>Gestion Horarios</a>
            <a href="<?php echo base_url('index.php/Visitante/index#'); ?>" class="nav-item nav-link <?php echo is_active('Visitante') ? 'active' : ''; ?>"><i class="fa fa-users me-2"></i>Visitantes</a>
          
            <?php if($this->session->userdata('Rol') === 'admin'): ?>
            <a href="<?php echo base_url('index.php/Usuario/lista_usuarios#'); ?>" class="nav-item nav-link <?php echo is_active('Usuario') ? 'active' : ''; ?>">
            <i class="fa fa-table me-2"></i>Usuarios</a>
            <?php endif; ?>
         
            <?php if($this->session->userdata('Rol') === 'admin'): ?>
            <a href="<?php echo base_url('index.php/Reportes/index#'); ?>" class="nav-item nav-link <?php echo is_active('Reportes_controller') ? 'active' : ''; ?>">
            <i class="far fa-file-alt me-2"></i>Reportes</a>
            <?php endif; ?>

         </div>
    </nav>
</div>

<div class="content">
    <!-- Navbar -->
    <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
        <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
            <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
        </a>
        <a href="#" class="sidebar-toggler flex-shrink-0">
            <i class="fa fa-bars"></i>
        </a>
        <form class="d-none d-md-flex ms-4">
            <input class="form-control border-0" type="search" placeholder="Buscar">
        </form>
        <div class="navbar-nav align-items-center ms-auto">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle" src="<?php echo $imgSrc; ?>" alt="" style="width: 40px; height: 40px;">
                    <span class="d-none d-lg-inline-flex"><?php echo $this->session->userdata('NombreCompleto'); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                    <a href="<?php echo site_url('usuario/editar_perfil'); ?>" class="dropdown-item">Mi Perfil</a>
                    <a href="<?php echo base_url('index.php/auth/logout'); ?>" class="dropdown-item">Cerrar Sesion</a>
                </div>
            </div>
        </div>
    </nav>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Agroflori</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="<?php echo base_url('assets/dashmin/img/favicon.ico'); ?>" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="<?php echo base_url('assets/dashmin/lib/owlcarousel/assets/owl.carousel.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/dashmin/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css'); ?>" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?php echo base_url('assets/dashmin/css/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="<?php echo base_url('assets/dashmin/css/style.css'); ?>" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1f1f1f;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #009CFF;
            box-shadow: 0 0 0 0.2rem rgba(0, 156, 255, 0.25);
        }
        .login-container {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .custom-form {
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3 login-container">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <a href="index.html" class="">
                                <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>AGROFLORI</h3>
                            </a>
                            <h3>Iniciar Sesión</h3>
                        </div>
                        
                        <?php
                        $mensaje = "";
                        switch ($msg) {
                            case '1':
                                $mensaje = "Gracias por usar el sistema";
                                break;
                            case '2':
                                $mensaje = "Usuario no identificado";
                                break;
                            case '3':
                                $mensaje = "Acceso no válido - Favor inicie sesión";
                                break;
                            default:
                                $mensaje = "";
                                break;
                        }
                        ?>
                        
                        <?php if($mensaje): ?>
                            <div class="alert alert-info mb-4"><?php echo $mensaje; ?></div>
                        <?php endif; ?>

                        <?php echo form_open('auth/validar', array('id' => 'form1', 'class' => 'custom-form')); ?>
                            <div class="form-group">
                                <label for="floatingInput" class="form-label">Usuario</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="floatingInput" 
                                       name="NombreUsuario" 
                                       placeholder="Ingrese su usuario" 
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="floatingPassword" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="floatingPassword" 
                                       name="Clave" 
                                       placeholder="Ingrese su contraseña" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">
                                Iniciar sesión
                            </button>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/chart/chart.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/easing/easing.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/waypoints/waypoints.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/owlcarousel/owl.carousel.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/tempusdominus/js/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/tempusdominus/js/moment-timezone.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/dashmin/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>

    <!-- Template Javascript -->
    <script src="<?php echo base_url('assets/dashmin/js/main.js'); ?>"></script>
</body>
</html>
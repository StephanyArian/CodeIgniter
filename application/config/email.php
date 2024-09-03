<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.googlemail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'ignaciostephany127@gmail.com'; // tu dirección de correo
$config['smtp_pass'] = 'wzelsvavocekqqdf'; // tu contraseña de correo
$config['smtp_crypto'] ='ssl'; // Asegúrate de que este valor esté presente
$config['mailtype'] = 'html';
$config['charset']  = 'utf-8';
$config['newline']  = "\r\n";
$config['wordwrap'] = TRUE;

?>
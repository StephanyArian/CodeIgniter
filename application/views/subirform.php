<br><br>
<h1>Subir fotografia del usuario</h1>
<br>

<?php
echo form_open_multipart("Usuario/subir");
?>
<input type="hidden" class="form-control" name="idUsuarios" value="<?php echo $idUsuarios; ?>">
<input type="file" name="userfile">
<button type="submit" class="btn btn-primary">Subir</button>
	
<?php
echo form_close();
?>

    <!-- Fin de la barra de navegación -->

    <!-- Inicio del contenido principal -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">

          <br><br>
           <h1>Subir fotografia del usuario</h1>
           <br>

           <?php
            echo form_open_multipart("usuario/subir");
            ?>
            <input type="hidden" class="form-control" name="idUsuarios" value="<?php echo $idUsuarios; ?>">
            <input type="file" name="userfile">
           <button type="submit" class="btn btn-primary">Subir</button>
	
          <?php
           echo form_close();
           ?>
        </div>
        </div>
    </div>
 
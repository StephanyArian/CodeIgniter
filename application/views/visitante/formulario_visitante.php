<div class="container">
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

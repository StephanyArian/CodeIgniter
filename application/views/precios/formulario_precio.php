<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2><?php echo isset($precio) ? 'Editar Precio' : 'Agregar Nuevo Precio'; ?></h2>
                <?php echo validation_errors(); ?>
                <?php echo form_open(isset($precio) ? 'precios/editar/'.$precio['id'] : 'precios/agregar'); ?>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="">Seleccione un tipo</option>
                            <?php foreach ($tipos_permitidos as $tipo): ?>
                                <option value="<?php echo $tipo; ?>" <?php echo (isset($precio) && $precio['tipo'] == $tipo) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst(str_replace('_', ' ', $tipo)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo isset($precio) ? $precio['precio'] : set_value('precio'); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><?php echo isset($precio) ? 'Actualizar' : 'Agregar'; ?></button>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
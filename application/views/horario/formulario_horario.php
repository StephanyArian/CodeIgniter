<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2><?php echo isset($horario) ? 'Editar Horario' : 'Agregar Horario'; ?></h2>
                
                <?php echo form_open(isset($horario) ? 'horario/editar/'.$horario['idHorarios'] : 'horario/agregar'); ?>
                
                <div class="mb-3">
                    <label for="Dia" class="form-label">Fecha</label>
                    <input type="date" class="form-control" name="Dia" value="<?php echo isset($horario) ? $horario['Dia'] : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="HoraEntrada" class="form-label">Hora de Entrada</label>
                    <input type="time" class="form-control" name="HoraEntrada" value="<?php echo isset($horario) ? $horario['HoraEntrada'] : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="HoraCierre" class="form-label">Hora de Cierre</label>
                    <input type="time" class="form-control" name="HoraCierre" value="<?php echo isset($horario) ? $horario['HoraCierre'] : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="MaxVisitantes" class="form-label">Capacidad Máxima</label>
                    <input type="number" class="form-control" name="MaxVisitantes" value="<?php echo isset($horario) ? $horario['MaxVisitantes'] : ''; ?>" required min="0">
                </div>

                <div class="mb-3">
                    <label for="Estado" class="form-label">Estado</label>
                    <select class="form-control" name="Estado" required>
                        <option value="1" <?php echo (isset($horario) && $horario['Estado'] == 1) ? 'selected' : ''; ?>>Abierto</option>
                        <option value="0" <?php echo (isset($horario) && $horario['Estado'] == 0) ? 'selected' : ''; ?>>Cerrado</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary"><?php echo isset($horario) ? 'Actualizar' : 'Agregar'; ?></button>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
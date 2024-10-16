<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                 <h2>Reporte de Ocupación de Horarios</h2>
                 <table border="1" cellpadding="5" cellspacing="0" width="100%">
                     <tr>
                          <th>Día</th>
                          <th>Hora Entrada</th>
                         <th>Hora Cierre</th>
                          <th>Capacidad Máxima</th>
                         <th>Visitantes Actuales</th>
                         <th>% Ocupación</th>
                         </tr>
                         <?php foreach ($horarios as $horario): ?>
                       <tr>
                         <td><?php echo $horario['Dia']; ?></td>
                          <td><?php echo $horario['HoraEntrada']; ?></td>
                         <td><?php echo $horario['HoraCierre']; ?></td>
                         <td><?php echo $horario['MaxVisitantes']; ?></td>
                         <td><?php echo $horario['visitantes_actuales']; ?></td>
                         <td><?php echo round(($horario['visitantes_actuales'] / $horario['MaxVisitantes']) * 100, 2); ?>%</td>
                      </tr>
                      <?php endforeach; ?>
                 </table>
          </div>
       </div>
  </div>
</div>
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
              <h2>Reporte de Estadísticas de Visitantes</h2>
              <h3>Resumen General</h3>
             <table border="1" cellpadding="5" cellspacing="0" width="100%">
                  <tr>
                     <th>Total Visitantes</th>
                     <th>Adulto Mayor</th>
                     <th>Adulto</th>
                     <th>Infante</th>
                 </tr>
                 <tr>
                     <td><?php echo $estadisticas[0]['total_visitantes']; ?></td>
                     <td><?php echo $estadisticas[0]['total_adulto_mayor']; ?></td>
                     <td><?php echo $estadisticas[0]['total_adulto']; ?></td>
                     <td><?php echo $estadisticas[0]['total_infante']; ?></td>
                 </tr>
             </table>

             <h3>Visitas por Día de la Semana</h3>
             <table border="1" cellpadding="5" cellspacing="0" width="100%">
                 <tr>
                     <th>Día</th>
                     <th>Número de Visitas</th>
                 </tr>
                 <?php foreach ($estadisticas as $estadistica): ?>
                 <tr>
                     <td><?php echo $estadistica['Dia']; ?></td>
                     <td><?php echo $estadistica['visitas_por_dia']; ?></td>
                 </tr>
                 <?php endforeach; ?>
             </table>       
          </div>
       </div>
  </div>
</div>
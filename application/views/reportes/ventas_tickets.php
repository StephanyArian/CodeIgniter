<!-- application/views/visitante/confirmar_venta.php -->
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
            <h2>Reporte de Ventas de Tickets</h2>
             <table border="1" cellpadding="5" cellspacing="0" width="100%">
             <tr>
               <th>Fecha</th>
             <th>Total Tickets</th>
              <th>Adulto Mayor</th>
             <th>Adulto</th>
              <th>Infante</th>
             <th>Ingresos Totales</th>
            </tr>
            <?php foreach ($ventas as $venta): ?>
            <tr>
              <td><?php echo $venta['fecha']; ?></td>
              <td><?php echo $venta['total_tickets']; ?></td>
             <td><?php echo $venta['total_adulto_mayor']; ?></td>
             <td><?php echo $venta['total_adulto']; ?></td>
             <td><?php echo $venta['total_infante']; ?></td>
             <td><?php echo number_format($venta['ingresos_totales'], 2); ?> Bs.</td>
          </tr>
           <?php endforeach; ?>
           </table>
            </div>
        </div>
    </div>
</div>
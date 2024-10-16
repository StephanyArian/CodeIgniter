<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
               
            <h2>Reporte de Estructura de Precios</h2>
          <table border="1" cellpadding="5" cellspacing="0" width="100%">
              <tr>
                  <th>Tipo de Entrada</th>
                 <th>Precio</th>
                 <th>Última Actualización</th>
                 </tr>
                 <?php foreach ($precios as $precio): ?>
             <tr>
              <td><?php echo ucfirst(str_replace('_', ' ', $precio['tipo'])); ?></td>
             <td><?php echo number_format($precio['precio'], 2); ?> Bs.</td>
              <td><?php echo $precio['fecha_actualizacion']; ?></td>
              </tr>
             <?php endforeach; ?>
          </table>
          </div>
       </div>
  </div>
</div>
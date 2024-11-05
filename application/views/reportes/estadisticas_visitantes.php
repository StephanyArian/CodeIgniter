<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded h-100 p-4">
                <h2>Reporte de Estadisticas de Visitantes</h2>
                
                <h3>Resumen General</h3>
                <table border="1" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th>Total Visitantes</th>
                        <th>Adulto Mayor</th>
                        <th>Adulto</th>
                        <th>Infante</th>
                        <th>Tipo mas comun</th>
                    </tr>
                    <tr>
                        <td><?php echo $estadisticas[0]['total_visitantes']; ?></td>
                        <td><?php echo $estadisticas['estadisticas_generales']['total_adulto_mayor']; ?></td>
                        <td><?php echo $estadisticas['estadisticas_generales']['total_adulto']; ?></td>
                        <td><?php echo $estadisticas['estadisticas_generales']['total_infante']; ?></td>
                        <td><?php echo $estadisticas['estadisticas_generales']['tipo_visitante_mas_comun']; ?></td>
                    </tr>
                </table>

                <h3>Promedio de Visitantes por Tipo</h3>
                <table border="1" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th>Promedio Adulto Mayor</th>
                        <th>Promedio Adulto</th>
                        <th>Promedio Infante</th>
                    </tr>
                    <tr>
                        <td><?php echo number_format($estadisticas[0]['promedio_adulto_mayor'], 2); ?></td>
                        <td><?php echo number_format($estadisticas[0]['promedio_adulto'], 2); ?></td>
                        <td><?php echo number_format($estadisticas[0]['promedio_infante'], 2); ?></td>
                    </tr>
                </table>

                <h3>Visitas por Dia</h3>
                <table border="1" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <th>Dia</th>
                        <th>Numero de Visitas</th>
                        <th>Adulto Mayor</th>
                        <th>Adulto</th>
                        <th>Infante</th>
                    </tr>
                    <?php foreach ($estadisticas as $key => $estadistica): ?>
                        <?php if ($key !== 'estadisticas_generales'): ?>
                        <tr>
                            <td><?php echo $estadistica['Dia']; ?></td>
                            <td><?php echo $estadistica['visitas_por_dia']; ?></td>
                            <td><?php echo $estadistica['total_adulto_mayor']; ?></td>
                            <td><?php echo $estadistica['total_adulto']; ?></td>
                            <td><?php echo $estadistica['total_infante']; ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
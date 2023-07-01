<?php
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();


$sqlTotalEquipos = "SELECT COUNT(*) AS total FROM equipos";
$consultaTotalEquipos = $conexionBD->query($sqlTotalEquipos);
$totalEquipos = $consultaTotalEquipos->fetchColumn();

// Realiza la consulta para obtener el total de equipos disponibles
$sqlTotalEquiposDisponibles = "SELECT COUNT(*) AS total FROM equipos WHERE Disponibilidad = 1";
$consultaTotalEquiposDisponibles = $conexionBD->query($sqlTotalEquiposDisponibles);
$totalEquiposDisponibles = $consultaTotalEquiposDisponibles->fetchColumn();

$sqltotalpres="SELECT COUNT(*) AS total FROM prestamos WHERE EstadoPrestamo= 1";
$consultprestamos= $conexionBD->query($sqltotalpres);
$totalprestamos= $consultprestamos->fetchColumn();  


?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../estilos/complementos.css">
</head>
<body>
<div class="containers">
    <div class="row">
        <div class="col-6">
            <br>
            <div class="cards">
                <div class="card-header">Resumen de Equipos</div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Total de Equipos Registrados</th>
                                <th>Total de Equipos Disponibles</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $totalEquipos; ?></td>
                                <td><?php echo $totalEquiposDisponibles; ?></td>
                                <td>
                                    <a href="vista_equipos.php" class="btns">Ver Registros</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <br>
            <div class="cards">
                <div class="card-header">Resumen de Préstamos</div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Total de Préstamos Activos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $totalprestamos; ?></td>
                                <td>
                                    <a href="prestamos_realizados.php" class="btns">Ver Préstamos</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

</body>
</html>
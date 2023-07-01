

<?php
include('../templates/cabecera.php');
include('../secciones/prestamos.php');
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$sqlLastId = "SELECT MAX(IdPrestamo) AS LastId FROM prestamos";
$resultLastId = $conexionBD->query($sqlLastId);
$lastId = $resultLastId->fetch(PDO::FETCH_ASSOC);
$IdPrestamo_deseado = $lastId['LastId'];

$sqlreporte = "SELECT p.IdPrestamo, u.NombreUser, p.FechaPrestamo, p.FechaDevolucion, p.EstadoPrestamo, p.prestamoper, a.NombreArea, COUNT(dp.IdPrestamo) AS CantidadEquipos 
                FROM prestamos p 
                JOIN detalleprestamos dp ON p.IdPrestamo = dp.IdPrestamo 
                JOIN areas a ON p.IdArea = a.IdArea
                JOIN usuarios u ON p.IdUser=u.IdUser
                WHERE p.IdPrestamo = $IdPrestamo_deseado 
                GROUP BY p.IdPrestamo";
$reporteprestamo = $conexionBD->query($sqlreporte);
$prestamo = $reporteprestamo->fetch(PDO::FETCH_ASSOC);

$consultaEquipos = $conexionBD->prepare("SELECT e.IdEquipo, e.NombreEquipo
                                       FROM detalleprestamos dp
                                       JOIN equipos e ON dp.IdEquipo = e.IdEquipo
                                       WHERE dp.IdPrestamo = :prestamoId");
$consultaEquipos->execute(array(':prestamoId' => $IdPrestamo_deseado));
$equipos = $consultaEquipos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmación de Préstamo</title>
  <!-- Incluir los estilos de Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Incluir Sweet Alert 2 -->
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../estilos/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <script>
      Swal.fire({
        icon: "success",
        title: "Préstamo generado",
        text: "El préstamo se generó correctamente."
      }).then(function(result) {
        if (result.isConfirmed) {
          // Redireccionar a la página de realizar nuevo préstamo
          //window.location.href = "reporte_prestamo.php";
        }
      });
    </script>
    
    <div class="card">
        <div class="card-header">Información del Préstamo</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>IdPrestamo</th>
                        <th>Nombre de Usuario</th>
                        <th>FechaPrestamo</th>
                        <th>Fecha devolucion aproximada</th>
                        <th>EstadoPrestamo</th>
                        <th>PrestamoPersona</th>
                        <th>Nombre de Area</th>
                        <th>CantidadEquipos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if ($prestamo) {
                        echo "<tr>";
                        echo "<td>{$prestamo['IdPrestamo']}</td>";
                        echo "<td>{$prestamo['NombreUser']}</td>";
                        echo "<td>{$prestamo['FechaPrestamo']}</td>";
                        echo "<td>{$prestamo['FechaDevolucion']}</td>";
                        $estadoPrestamo = $prestamo['EstadoPrestamo'] == 1 ? 'Activo' : 'Terminado';
                        echo "<td>{$estadoPrestamo}</td>";
                        echo "<td>{$prestamo['prestamoper']}</td>";
                        echo "<td>{$prestamo['NombreArea']}</td>";
                        echo "<td>{$prestamo['CantidadEquipos']}</td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='9'>No se encontró información del préstamo</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
  
    <div class="mt-3">
      <a href="vista_prestamos.php" class="btn btn-primary">Realizar nuevo préstamo</a>
      <a href="prestamos_realizados.php" class="btn btn-info">Ver préstamos realizados</a>
      <a href="../templates/imprimir_prestamo.php" class="btn btn-success">Imprimir préstamo generado</a>
    </div>
  </div>

  <!-- Incluir los scripts de Bootstrap 5 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 
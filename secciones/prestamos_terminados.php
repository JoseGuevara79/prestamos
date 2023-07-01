<?php 
include('../templates/cabecera.php');
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$sqltodos="SELECT p.IdPrestamo, u.NombreUser, p.FechaPrestamo, p.FechaDevolucion, p.FechaFinalizar, p.EstadoPrestamo, p.prestamoper, a.NombreArea, COUNT(dp.IdPrestamo) AS CantidadEquipos 
            FROM prestamos p 
            JOIN detalleprestamos dp ON p.IdPrestamo = dp.IdPrestamo 
            JOIN areas a ON p.IdArea = a.IdArea
            JOIN usuarios u ON p.IdUser=u.IdUser
            GROUP BY p.IdPrestamo";
$todosp=$conexionBD->query($sqltodos);
$listaprestamos = $todosp->fetch(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
  <title>Lista de prestamos</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../estilos/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

</head>

<body>

<div class="card">
  <div class="card-header">Lista de préstamos finalizados</div>
    <div class="card-body">
      <div class="table-responsive">
      <table id="prestamosTable" class="table">
                <thead>
                    <tr>
                        <th>IdPrestamo</th>
                        <th>Nombre de Usuario</th>
                        <th>FechaPrestamo</th>
                        <th>Fecha devolucion aproximada</th>
                        <th>FechaRealDevolucion</th>
                        <th>EstadoPrestamo</th>
                        <th>PrestamoPersona</th>
                        <th>Nombre de Area</th>
                        <th>CantidadEquipos</th>                  
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($listaprestamos = $todosp->fetch(PDO::FETCH_ASSOC)):
                ?>
                <?php if ($listaprestamos['EstadoPrestamo'] == 0): ?>
                    <tr>
                        <td><?php echo $listaprestamos['IdPrestamo']; ?></td>
                        <td><?php echo $listaprestamos['NombreUser']; ?></td>
                        <td><?php echo $listaprestamos['FechaPrestamo']; ?></td>
                        <td><?php echo $listaprestamos['FechaDevolucion']; ?></td>
                        <td><?php echo $listaprestamos['FechaFinalizar']; ?></td>
                        <td class="table-danger"><?php echo $listaprestamos['EstadoPrestamo']== 1 ? 'Activo' : 'Terminado'; ?></td>
                        <td><?php echo $listaprestamos['prestamoper']; ?></td>
                        <td><?php echo $listaprestamos['NombreArea']; ?></td>
                        <td><?php echo $listaprestamos['CantidadEquipos']; ?></td>
                    </tr>
                <?php endif; ?>
                <?php endwhile;

                
                    /* echo '<script>Swal.fire({
                          title: "Prestamo borrado correctamente",
                          icon: "success",
                          showCancelButton: false,
                          confirmButtonText: "OK"
                          }).then(function() {
                          window.location.href = "prestamos_terminados.php";
                          });</script>'; */
                ?>
                </tbody>
            </table>
          </div>
        </div>
    </div>
    <div class="mt-3">
      <a href="prestamos_realizados.php" class="btn btn-primary">Regresar</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script>
      $(document).ready(function () {
        $('#prestamosTable').DataTable({
          "paging": true,
          "lengthMenu": [5, 10, 15, 20, 25], // Define los registros a mostrar por página
          "pagingType": "numbers", // Tipo de paginación: números
          "pageLength": 5, // Registros por página inicial
          "language": {
            "paginate": {
              "previous": "Anterior", // Texto del botón de página anterior
              "next": "Siguiente" // Texto del botón de página siguiente
            },
            "lengthMenu": "Mostrar _MENU_ registros por página", // Texto del menú de selección de registros por página
            "zeroRecords": "No se encontraron registros", // Texto cuando no se encuentran registros
            "info": "Mostrando página _PAGE_ de _PAGES_", // Texto de información de paginación
            "infoEmpty": "No hay registros disponibles", // Texto cuando no hay registros disponibles
            "infoFiltered": "(filtrado de _MAX_ registros en total)" // Texto de información de registros filtrados
          }
        });
      });
    </script>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
  </body>
</html>

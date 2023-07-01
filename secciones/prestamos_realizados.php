<?php 
include('../templates/cabecera.php');
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$sqltodos="SELECT p.IdPrestamo, u.NombreUser, p.FechaPrestamo, p.FechaDevolucion, p.EstadoPrestamo, p.prestamoper, a.NombreArea, COUNT(dp.IdPrestamo) AS CantidadEquipos 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="../estilos/bootstrap.min.css">

    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">

        <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

</head>

<body>

<div class="card">
        <div class="card-header">Lista de préstamos realizados</div>
        <div class="card-body">
        <div class="table-responsive">
            <table id="prestamosTable" class="table">
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
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while ($listaprestamos = $todosp->fetch(PDO::FETCH_ASSOC)):
                ?>
                <?php if ($listaprestamos['EstadoPrestamo'] == 1): ?>
                    <tr>
                        <td><?php echo $listaprestamos['IdPrestamo']; ?></td>
                        <td><?php echo $listaprestamos['NombreUser']; ?></td>
                        <td><?php echo $listaprestamos['FechaPrestamo']; ?></td>
                        <td><?php echo $listaprestamos['FechaDevolucion']; ?></td>
                        <td><?php echo $listaprestamos['EstadoPrestamo']== 1 ? 'Activo' : 'Terminado'; ?></td>
                        <td><?php echo $listaprestamos['prestamoper']; ?></td>
                        <td><?php echo $listaprestamos['NombreArea']; ?></td>
                        <td><?php echo $listaprestamos['CantidadEquipos']; ?></td>

                        <?php $popupId = 'popup-' . $listaprestamos['IdPrestamo']; ?>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $popupId; ?>">Finalizar</button>
                            <a href="../templates/imprime_prestamo.php?id=<?php echo $listaprestamos['IdPrestamo']; ?>" class="btn btn-success">Imprimir</a>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="<?php echo $popupId; ?>" tabindex="-1" aria-labelledby="<?php echo $popupId; ?>Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="<?php echo $popupId; ?>Label">Prestamo seleccionado</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Fecha de devolucion real:</label>
                                            <input type="date" id="fechafinalizar" name="fechafinalizar" class="form-control">
                                            <input type="hidden" name="prestamo_id" value="<?php echo $listaprestamos['IdPrestamo']; ?>">
                                        </div>
                                        <h6>Nota*</h6>
                                        <label for="" class="form-label" style="color:#ECCF18">Recuerda que al registrar la fecha real de devolución, estarás finalizando el préstamo y confirmando la devolución de los equipos prestados.</label>
                                        <button type="submit" class="btn btn-primary" name="registrar">Registrar</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endwhile;

                // Verificar si se ha enviado el formulario
                  if (isset($_POST['registrar'])) {
                    // Obtener el ID del préstamo seleccionado
                    $prestamoId = $_POST['prestamo_id'];
                    $fechafin = $_POST['fechafinalizar'];

                    if (!empty($fechafin)) {

                    $sqlactualizar = "UPDATE prestamos SET FechaFinalizar = :fechafin, EstadoPrestamo = 0 WHERE IdPrestamo = :prestamoId";
                    $actualizarprestamo = $conexionBD->prepare($sqlactualizar);
                    $actualizarprestamo->execute(array(':fechafin' => $fechafin, ':prestamoId' => $prestamoId));
                    
                    // Obtener los equipos asociados al préstamo
                    $consultaEquipos = $conexionBD->prepare("SELECT IdEquipo FROM detalleprestamos WHERE IdPrestamo = :prestamoId");
                    $consultaEquipos->execute(array(':prestamoId' => $prestamoId));
                    $equipos = $consultaEquipos->fetchAll(PDO::FETCH_ASSOC);

                    // Actualizar la disponibilidad de los equipos a 1 (disponible)
                    foreach ($equipos as $equipo) {
                        $equipoId = $equipo['IdEquipo'];
                        $actualizarDisponibilidad = $conexionBD->prepare("UPDATE equipos SET Disponibilidad = 1 WHERE IdEquipo = :equipoId");
                        $actualizarDisponibilidad->execute(array(':equipoId' => $equipoId));
                    }
                    echo '<script>Swal.fire({
                          title: "Prestamo terminado correctamente",
                          icon: "success",
                          showCancelButton: false,
                          confirmButtonText: "OK"
                          }).then(function() {
                          window.location.href = "prestamos_realizados.php";
                          });</script>';
                     } else {
                    // Mostrar mensaje de error si el campo está vacío
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Por favor, ingresa la fecha de devolución real.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                        }
                    }
                
                ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
  
    <div class="mt-3">
      <a href="reporte_prestamo.php" class="btn btn-primary">Ultimo prestamo registrado</a>
      <a href="prestamos_terminados.php" class="btn btn-info">Ver prestamos terminados</a>
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
</body>
</html>

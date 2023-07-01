<!DOCTYPE html>
<html>
<head>
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>
<body>
<?php
include_once "../configuraciones/bd.php";

session_start();
$conexionBD = BD::crearInstancia();
//llamadas del formulario
$fechaPrestamo=isset($_POST['fecha_prestamo'])?$_POST['fecha_prestamo']:'';
$fechaDevolucion=isset($_POST['fecha_devolucion'])?$_POST['fecha_devolucion']:'';
$prestamoPersona=isset($_POST['nombre_persona'])?$_POST['nombre_persona']:'';

//usuario que inicio sesion
/* $idUsuario = isset($_SESSION['usuario']) ? $_SESSION['usuario']['IdUser'] : null;
$_SESSION['usuario']['id'] = $idUsuario; */
$idUsuario = isset($_SESSION['usuario']['IdUser']) ? $_SESSION['usuario']['IdUser'] : null;

//seleccion areas
$consultaAreas = $conexionBD->prepare("SELECT * FROM areas");
$consultaAreas->execute();
$listaAreas = $consultaAreas->fetchAll();

//seleccion equipos
$consultaEquipos = $conexionBD->prepare("SELECT * FROM equipos WHERE Disponibilidad = 1");
$consultaEquipos->execute();
$listaEquipos = $consultaEquipos->fetchAll();

$areasSeleccionadas = isset($_POST['areas']) ? $_POST['areas'] : array();

//$areasSeleccionadas = isset($_POST['areas']) ? $_POST['areas'][0] : null;

$equiposSeleccionados = isset($_POST['equipos']) ? $_POST['equipos'] : array();

$accion=isset($_POST['accion'])?$_POST['accion']:'';

// print_r($_POST);
// echo "ID de usuario: " . $idUsuario;

if ($accion != '') {
    switch ($accion) {

        case 'selecciona':
            $_SESSION['prestamo'] = array(
                'FechaPrestamo' => $fechaPrestamo,
                'FechaDevolucion' => $fechaDevolucion,
                'prestamoper' => $prestamoPersona,
                'IdArea' => $areasSeleccionadas
            );
            break;

        case 'agregar':
            if (!isset($_SESSION['prestamo'])) {
                $_SESSION['prestamo'] = array();
            }
            $contador = count($_SESSION['prestamo']) + 1;
            $_SESSION['prestamo'][$contador] = array(
                'IdEquipo' => $equiposSeleccionados    
            );
            break;
            
        case 'borrar':

            if (!empty($_POST['contador']) && isset($_SESSION['prestamo'][$_POST['contador']])) {
                $idBorrar = $_POST['contador'];
                unset($_SESSION['prestamo'][$idBorrar]);
                //recarga pagina actual
                header('Location: '.$_SERVER['PHP_SELF']);
                exit();
                }
            break; 

            case 'realizarprestamo':
                if (isset($_SESSION['prestamo']) && is_array($_SESSION['prestamo'])) {
                    $fechaPrestamo = $_SESSION['prestamo']['FechaPrestamo'];
                    $fechaDevolucion = $_SESSION['prestamo']['FechaDevolucion'];
                    $prestamoPersona = $_SESSION['prestamo']['prestamoper'];
                    $idArea = $_SESSION['prestamo']['IdArea'][0];
            
                    // Insertar datos en la tabla "prestamos"
                    $insertarPrestamo = $conexionBD->prepare("INSERT INTO prestamos (IdPrestamo, IdUser, FechaPrestamo, FechaDevolucion, EstadoPrestamo, Vigencia, prestamoper, IdArea) VALUES (NULL, ?, ?, ?, 1, 1, ?, ?)");
                    $insertarPrestamo->execute([$idUsuario, $fechaPrestamo, $fechaDevolucion, $prestamoPersona, $idArea]);
                    $idPrestamo = $conexionBD->lastInsertId(); // Obtener el último IdPrestamo insertado
            
                    // Insertar datos en la tabla "detalleprestamos"
                    foreach ($_SESSION['prestamo'] as $id => $prestamo) {
                        if ($id !== 'FechaPrestamo' && $id !== 'FechaDevolucion' && $id !=='prestamoper' && $id !== 'IdArea') {
                            if (is_array($prestamo) && isset($prestamo['IdEquipo']) && is_array($prestamo['IdEquipo']) && !empty($prestamo['IdEquipo'])) {
                                $cantidadEquipos = count($prestamo['IdEquipo']);
                                foreach ($prestamo['IdEquipo'] as $idEquipo) {
                                    if (!empty($idEquipo)) {
                                    $insertarDetallePrestamo = $conexionBD->prepare("INSERT INTO detalleprestamos (IdDetalle, CantidadEquipos, IdPrestamo, IdEquipo) VALUES (NULL, ?, ?, ?)");
                                    $insertarDetallePrestamo->execute([$cantidadEquipos, $idPrestamo, $idEquipo]);

                                    // Actualizar disponibilidad del equipo a 0 (no disponible)
                                    $actualizarDisponibilidad = $conexionBD->prepare("UPDATE equipos SET Disponibilidad = 0 WHERE IdEquipo = ?");
                                    $actualizarDisponibilidad->execute([$idEquipo]);
                                    }
                                }
                            }
                        }
                    }
            
                    // Cerrar la conexión a la base de datos
                    $conexionBD = null;
                    // Limpiar la sesión "prestamo"
                    unset($_SESSION['prestamo']);
            
                    // Redireccionar a la página de confirmación
                    header('Location: reporte_prestamo.php');
                    exit();
            
                } else {
                    // Si no hay datos de préstamo en la sesión, redireccionar a la página principal
                    header('Location: vista_prestamos.php');
                    exit();
                }
                break;

                case 'cancelar':
                    unset($_SESSION['prestamo']);
                    header('Location: vista_prestamos.php');
                    exit();
                break;
            
    }
}
// Función para obtener el nombre del área por su ID
function obtenerNombreArea($idArea) {
    global $listaAreas;

    foreach ($listaAreas as $area) {
        if ($area['IdArea'] == $idArea) {
            return $area['NombreArea'];
        }
    }
    // Mostrar alerta si el área no se encontró
    echo '<script>';
    echo 'Swal.fire({';
    echo '   icon: "error",';
    echo '   title: "Error",';
    echo '   text: "El área seleccionada no existe, por favor selecciona una area",';
    echo '})';
    echo '</script>';
    exit();
}
function obtenerNombreEquipo($idEquipo) {
    global $listaEquipos;

    foreach ($listaEquipos as $equipo) {
        if ($equipo['IdEquipo'] == $idEquipo) {
            return $equipo['NombreEquipo'];
        }
    }
    echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, selecciona un equipo valido',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";
                                  
}

?>
</body>
</html>
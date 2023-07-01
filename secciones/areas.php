<!DOCTYPE html>
<html>
<head>
  <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="../node_modules/sweetalert2/dist/sweetalert2.min.css">
</head>
<body>
<?php
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$ida=isset($_POST['id'])?$_POST['id']:'';
$nombre_areas=isset($_POST['nombre_area'])?$_POST['nombre_area']:'';
$localizacion=isset($_POST['localizacion'])?$_POST['localizacion']:'';
$accion=isset($_POST['accion'])?$_POST['accion']:'';
//print_r($_POST);

if($accion!=''){
    switch($accion){

        case 'agregar':

            $sql="INSERT INTO areas (IdArea, NombreArea, Localizacion) VALUES (NULL, :nombre_area, :localizacion)";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':nombre_area', $nombre_areas);
            $consulta->bindParam(':localizacion', $localizacion);
            $consulta->execute();

            // Verificar si la inserción fue exitosa
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'El area ha sido agregada correctamente.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'vista_areas.php';
                            }
                        });
                </script>";
                
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, completa los campos restantes',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";  
            }
            break;
            
        break;
        case 'editar':
            $sql="UPDATE areas SET NombreArea=:nombre_area, Localizacion= :localizacion WHERE IdArea=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$ida);
            $consulta->bindParam(':nombre_area', $nombre_areas);
            $consulta->bindParam(':localizacion', $localizacion);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El área ha sido actualiza correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Redirigir después de cerrar la alerta
                                window.location.href = 'vista_areas.php';
                            });
                </script>";
                
            } else {
                
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Aún no has modificado nada!',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";  
            }
        break;
        case 'borrar':
            try {
                $sql="DELETE FROM areas WHERE IdArea=:id";
                $consulta=$conexionBD->prepare($sql);
                $consulta->bindParam(':id',$ida);
                $consulta->execute();

                // Mostrar alerta de éxito con SweetAlert2
                echo "<script>
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'El área se ha borrado correctamente.',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                    }).then(() => {
                    // Redirigir después de cerrar la alerta
                    window.location.href = 'vista_areas.php';
                    });
              </script>";
                
                } catch (PDOException $e) {
                    // Mostrar mensaje de error genérico en caso de otra excepción PDO
                    echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Error',
                                  text: 'No se puede borrar el area seleccionada, debido a que existen registros relacionados en la base de datos.',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        // Redirigir después de cerrar la alerta
                                        window.location.href = 'vista_areas.php';
                                    });
        
                           </script>";
                }
            
        break;

        case 'seleccionar':
            $sql="SELECT * FROM areas WHERE IdArea=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$ida);
            $consulta->execute();
            $area=$consulta->fetch(PDO::FETCH_ASSOC);
            $nombre_areas=$area['NombreArea'];
            $localizacion=$area['Localizacion'];
            
        break;
        
        case 'cancelar':
            header('Location: vista_areas.php');
            exit();
        break;

    }
}


$consulta=$conexionBD->prepare("SELECT * FROM areas");
$consulta->execute();
$listaAreas=$consulta->fetchAll();
?>
</body>
</html>
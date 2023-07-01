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

$ide=isset($_POST['id'])?$_POST['id']:'';
$nombre_equipos=isset($_POST['nombre_equipo'])?$_POST['nombre_equipo']:'';


$categorias=isset($_POST['categorias'])?$_POST['categorias'] : array();


$marca=isset($_POST['marca_equipo'])?$_POST['marca_equipo']:'';
$numeroserie=isset($_POST['num_serie'])?$_POST['num_serie']:'';
$modelo=isset($_POST['modelo_equipo'])?$_POST['modelo_equipo']:'';
$description=isset($_POST['descripcion'])?$_POST['descripcion']:'';
$disponible=isset($_POST['disponibilidad'])?$_POST['disponibilidad']:'';
$accion=isset($_POST['accion'])?$_POST['accion']:'';
//print_r($_POST);

if ($accion != '') {
    switch ($accion) {
        
        case 'agregar':

                if (empty($id_categoria)) {
             
                    // Mostrar alerta indicando que no se ha seleccionado una categoría válida
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Advertencia',
                            text: 'Por favor, selecciona una categoría.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                            history.back(); // Regresar a la página anterior sin perder los valores
                            }
                        });
                        </script>";
                    //exit(); // Detener la ejecución del script para evitar el error posterior
                }

                $sql = "INSERT INTO equipos (IdEquipo, NombreEquipo, IdCategoria, Marca, NumSerie, Modelo, Descripcion, Disponibilidad) VALUES (NULL, :nombre_equipo, :id_categoria, :marca_equipo, :num_serie, :modelo_equipo, :descripcion, :disponibilidad)";
                $consulta = $conexionBD->prepare($sql);
                $consulta->bindParam(':nombre_equipo', $nombre_equipos);
                
                $consulta->bindParam(':marca_equipo', $marca);
                $consulta->bindParam(':num_serie', $numeroserie);
                $consulta->bindParam(':modelo_equipo', $modelo);
                $consulta->bindParam(':descripcion', $description);
                $consulta->bindParam(':disponibilidad', $disponible);

                foreach ($categorias as $categoria) {
                    if (!empty($categoria)) {
                        $id_categoria = $categoria;
                        $consulta->bindParam(':id_categoria', $id_categoria);
                        $consulta->execute();
                    }
                }

                // Verificar si la inserción fue exitosa
                if ($consulta->rowCount() > 0) {
                    // Mostrar mensaje de éxito con SweetAlert2
                    echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El equipo ha sido agregado correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'vista_equipos.php';
                                }
                            });
                    </script>";
                    
                } else {
                    // Mostrar mensaje de error si no se pudo insertar la categoría
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al agregar el equipo.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";  
                }
       
        break;
        
        case 'editar':
            if (empty($id_categoria)) {
             
                // Mostrar alerta indicando que no se ha seleccionado una categoría válida
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, selecciona una categoría valida',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                        history.back(); // Regresar a la página anterior sin perder los valores
                        }
                    });
                    </script>";
                //exit(); // Detener la ejecución del script para evitar el error posterior
            }
        
            $sql="UPDATE equipos SET NombreEquipo=:nombre_equipo, IdCategoria= :id_categoria, Marca=:marca_equipo, NumSerie=:num_serie, Modelo=:modelo_equipo, Descripcion=:descripcion, Disponibilidad=:disponibilidad WHERE IdEquipo=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$ide);
            $consulta->bindParam(':nombre_equipo', $nombre_equipos);

            $id_categoria = isset($_POST['categorias']) ? $_POST['categorias'][0] : null; // Obtener el valor de la categoría seleccionada
            $consulta->bindParam(':id_categoria', $id_categoria);

            $consulta->bindParam(':marca_equipo', $marca);
            $consulta->bindParam(':num_serie', $numeroserie);
            $consulta->bindParam(':modelo_equipo', $modelo);
            $consulta->bindParam(':descripcion', $description);
            $consulta->bindParam(':disponibilidad', $disponible);
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El equipo ha sido actualizado correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Redirigir después de cerrar la alerta
                                window.location.href = 'vista_equipos.php';
                            });
                </script>";
                
            } else {
                // Mostrar mensaje de error si no se pudo insertar
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Aun no has modificado nada.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";  
            }
            
        break;

        case 'borrar':
            
            try {
                $sql="DELETE FROM equipos WHERE IdEquipo=:id";
                $consulta=$conexionBD->prepare($sql);
                $consulta->bindParam(':id',$ide);
                $consulta->execute();
                echo "<script>
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'El equipo se ha borrado correctamente.',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                    }).then(() => {
                    // Redirigir después de cerrar la alerta
                    window.location.href = 'vista_equipos.php';
                    });
                    </script>";
                } catch (PDOException $e) {
                    // Mostrar mensaje de error genérico en caso de otra excepción PDO
                    echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Error',
                                  text: 'No se puede borrar el equipo seleccionado, debido a que existen registros relacionados en la base de datos.',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        // Redirigir después de cerrar la alerta
                                        window.location.href = 'vista_equipos.php';
                                    });
        
                           </script>";
                }
        break;

        case 'seleccionar':
            $sql="SELECT * FROM equipos WHERE IdEquipo=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$ide);
            $consulta->execute();
            $equipo=$consulta->fetch(PDO::FETCH_ASSOC);
            $nombre_equipos=$equipo['NombreEquipo'];
            $id_categoria=$equipo['IdCategoria'];
            $marca=$equipo['Marca'];
            $numeroserie=$equipo['NumSerie'];
            $modelo=$equipo['Modelo'];
            $description=$equipo['Descripcion'];
            $disponible=$equipo['Disponibilidad'];  

            $categorias = array($id_categoria);
        break;

        case 'cancelar':
            unset($_POST); // Eliminar los datos enviados por el método POST
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        break;
        
    }
}

$consulta=$conexionBD->prepare("SELECT * FROM equipos");
$consulta->execute();
$listaEquipos=$consulta->fetchAll();


$consultaCategorias = $conexionBD->prepare("SELECT * FROM categorias");
$consultaCategorias->execute();
$listaCategorias = $consultaCategorias->fetchAll();


?>
</body>
</html>
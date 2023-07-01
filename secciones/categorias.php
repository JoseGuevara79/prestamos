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

$id=isset($_POST['id'])?$_POST['id']:'';
$nombre_categoria=isset($_POST['nombre_categoria'])?$_POST['nombre_categoria']:'';
$accion=isset($_POST['accion'])?$_POST['accion']:'';
//print_r($_POST);
if($accion!=''){
    switch($accion){

        case 'agregar':
            $sql = "INSERT INTO categorias (IdCategoria, NombreCategoria) VALUES (NULL, :nombre_categoria)";
            $consulta = $conexionBD->prepare($sql);
            $consulta->bindParam(':nombre_categoria', $nombre_categoria);
            $consulta->execute();
        
            // Verificar si la inserción fue exitosa
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'La categoría ha sido agregada correctamente.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'vista_categorias.php';
                            }
                        });
                </script>";
                
            } else {
                // Mostrar mensaje de error si no se pudo insertar la categoría
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Ocurrió un error al agregar la categoría, completa los campos',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    });
                </script>";  
            }
            break;
        
        case 'editar':
            $sql="UPDATE categorias SET NombreCategoria=:nombre_categoria WHERE IdCategoria=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$id);
            $consulta->bindParam(':nombre_categoria',$nombre_categoria);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'La categoría ha sido actualiza correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Redirigir después de cerrar la alerta
                                window.location.href = 'vista_categorias.php';
                            });
                </script>";
                
            } else {
                // Mostrar mensaje de error si no se pudo insertar la categoría
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
                    /* header('Location: vista_categorias.php?');
                    exit(); */
                    try {
                        $sql = "DELETE FROM categorias WHERE IdCategoria=:id";
                        $consulta = $conexionBD->prepare($sql);
                        $consulta->bindParam(':id', $id);
                        $consulta->execute();
                        
                        // Mostrar alerta de éxito con SweetAlert2
                        echo "<script>
                                Swal.fire({
                                  icon: 'success',
                                  title: 'Éxito',
                                  text: 'La categoría se ha borrado correctamente.',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                    // Redirigir después de cerrar la alerta
                                    window.location.href = 'vista_categorias.php';
                                    });
                              </script>";
                    } catch (PDOException $e) {
                        // Mostrar mensaje de error genérico en caso de excepción PDO
                        echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Error',
                                  text: 'No se puede borrar la categoria seleccionada, debido a que existen registros relacionados en la base de datos.',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        // Redirigir después de cerrar la alerta
                                        window.location.href = 'vista_categorias.php';
                                    });
        
                               </script>";
                    }
        break;

        case 'seleccionar':
            $sql="SELECT * FROM categorias WHERE IdCategoria=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$id);
            $consulta->execute();
            $categoria=$consulta->fetch(PDO::FETCH_ASSOC);
            $nombre_categoria=$categoria['NombreCategoria'];
            
        break;

        case 'cancelar':
            header('Location: vista_categorias.php?');
            exit();  
        break;
    }
}


$consulta=$conexionBD->prepare("SELECT * FROM categorias");
$consulta->execute();
$listaCategorias=$consulta->fetchAll();


?>
</body>
</html>



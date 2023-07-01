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

$idu=isset($_POST['id'])?$_POST['id']:'';
$nombre_usuarios=isset($_POST['nombre_usuario'])?$_POST['nombre_usuario']:'';
$contras=isset($_POST['contrase'])?$_POST['contrase']:'';
$email=isset($_POST['correo'])?$_POST['correo']:'';
$accion=isset($_POST['accion'])?$_POST['accion']:'';
//print_r($_POST);


if($accion!=''){
    switch($accion){

        case 'agregar':

            $sql="INSERT INTO usuarios (IdUser, NombreUser, Password, Correo) VALUES (NULL, :nombre_usuario, :contrase, :correo)";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':nombre_usuario', $nombre_usuarios);
            $consulta->bindParam(':contrase', $contras);
            $consulta->bindParam(':correo', $email);
            $consulta->execute();
            
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El usuario ha sido agregado correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Redirigir después de cerrar la alerta
                                window.location.href = 'vista_usuarios.php';
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
        case 'editar':
            $sql="UPDATE usuarios SET NombreUser=:nombre_usuario, Password= :contrase, Correo= :correo WHERE IdUser=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$idu);
            $consulta->bindParam(':nombre_usuario', $nombre_usuarios);
            $consulta->bindParam(':contrase', $contras);
            $consulta->bindParam(':correo', $email);
            $consulta->execute();
            if ($consulta->rowCount() > 0) {
                // Mostrar mensaje de éxito con SweetAlert2
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El usuario ha sido actualizado correctamente.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                            }).then(() => {
                                // Redirigir después de cerrar la alerta
                                window.location.href = 'vista_usuarios.php';
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
                $sql="DELETE FROM usuarios WHERE IdUser=:id";
                $consulta=$conexionBD->prepare($sql);
                $consulta->bindParam(':id',$idu);
                $consulta->execute();
                
                // Mostrar alerta de éxito con SweetAlert2
                echo "<script>
                Swal.fire({
                  icon: 'success',
                  title: 'Éxito',
                  text: 'El usuario se ha borrado correctamente.',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Aceptar'
                    }).then(() => {
                    // Redirigir después de cerrar la alerta
                    window.location.href = 'vista_usuarios.php';
                    });
              </script>";
                
                } catch (PDOException $e) {
                    // Mostrar mensaje de error genérico en caso de otra excepción PDO
                    echo "<script>
                                Swal.fire({
                                  icon: 'error',
                                  title: 'Error',
                                  text: 'No se puede borrar el usuario seleccionado, debido a que existen registros relacionados en la base de datos.',
                                  confirmButtonColor: '#3085d6',
                                  confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        // Redirigir después de cerrar la alerta
                                        window.location.href = 'vista_usuarios.php';
                                    });
        
                           </script>";
                }
            
        break;

        case 'seleccionar':
            $sql="SELECT * FROM usuarios WHERE IdUser=:id";
            $consulta=$conexionBD->prepare($sql);
            $consulta->bindParam(':id',$idu);
            $consulta->execute();
            $usuario=$consulta->fetch(PDO::FETCH_ASSOC);
            $nombre_usuarios=$usuario['NombreUser'];
            $contras=$usuario['Password'];
            $email=$usuario['Correo'];
            
        break;

        case 'cancelar':
            header('Location: vista_usuarios.php');
            exit();
        break;

    }
}


$consulta=$conexionBD->prepare("SELECT * FROM usuarios");
$consulta->execute();
$listaUsuarios=$consulta->fetchAll();

?>
</body>
</html>
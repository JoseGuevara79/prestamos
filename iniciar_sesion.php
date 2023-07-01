
<?php
include_once "configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$contrasenia = isset($_POST['contrasenia']) ? $_POST['contrasenia'] : '';

// Validar el inicio de sesión
$sql = "SELECT * FROM usuarios WHERE NombreUser = :usuario AND Password = :contrasenia";
$consulta = $conexionBD->prepare($sql);
$consulta->bindParam(':usuario', $usuario);
$consulta->bindParam(':contrasenia', $contrasenia);
$consulta->execute();
$usuarioEncontrado = $consulta->fetch(PDO::FETCH_ASSOC);

if ($usuarioEncontrado) {
    session_start();
    $_SESSION['usuario'] = $usuarioEncontrado; // Almacena todos los datos del usuario en $_SESSION['usuario']
    $_SESSION['idUser'] = $usuarioEncontrado['IdUser'];
    $_SESSION['correo'] = $usuarioEncontrado['Correo'];
    $_SESSION['usuario']['id'] = $_SESSION['idUser']; // Asigna el valor del IdUser a $_SESSION['usuario']['id']
    header('Location: secciones/index.php'); // Redirige a la página de inicio
    
} else {
    // Inicio de sesión fallido
    $mensaje = "Credenciales incorrectas. Por favor, verifique sus datos.";
    header("Location: index.php?mensaje=" . urlencode($mensaje)); // Redirige a la página de inicio de sesión con el mensaje de error
}
?>


<?php
ob_start(); // Iniciar el búfer de salida
include_once "../configuraciones/bd.php";
include('../librerias/tcpdf/tcpdf.php');
include('../secciones/prestamos_realizados.php');

// Obtener el IdPrestamo desde la URL
if (!isset($_GET['id'])) {
    die("No se proporcionó el IdPrestamo.");
}
$idPrestamo = $_GET['id'];

// Crear instancia de la clase BD para la conexión a la base de datos
$bd = BD::crearInstancia();

try {
    // Consultar los detalles del préstamo
    $consulta = $bd->prepare("SELECT p.IdPrestamo, u.NombreUser, p.FechaPrestamo, p.FechaDevolucion, p.EstadoPrestamo, p.prestamoper, a.NombreArea, COUNT(dp.IdPrestamo) AS CantidadEquipos, GROUP_CONCAT(e.NombreEquipo SEPARATOR ', ') AS EquiposPrestados
    FROM prestamos p
    JOIN detalleprestamos dp ON p.IdPrestamo = dp.IdPrestamo
    JOIN areas a ON p.IdArea = a.IdArea
    JOIN usuarios u ON p.IdUser = u.IdUser
    JOIN equipos e ON dp.IdEquipo = e.IdEquipo
    WHERE p.IdPrestamo = :id
    GROUP BY p.IdPrestamo");
                             
    $consulta->execute(['id' => $idPrestamo]);
    $prestamo = $consulta->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el préstamo
    if (!$prestamo) {
        die("No se encontró el préstamo con el IdPrestamo especificado.");
    }

    // Obtener los atributos del préstamo
    $NombreUser = $prestamo['NombreUser'];
    $FechaPrestamo = $prestamo['FechaPrestamo'];
    $EstadoPrestamo = $prestamo['EstadoPrestamo'] == 1 ? 'Activo' : 'Desactivado';
    $FechaDevolucion = $prestamo['FechaDevolucion'];
    $PrestamoPer = $prestamo['prestamoper'];
    $NombreArea = $prestamo['NombreArea'];
    $CantidadEquipos = $prestamo['CantidadEquipos'];
    $equiposPrestados = $prestamo['EquiposPrestados'];

    // Crear instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer información del documento
    $pdf->SetCreator('Colegio de Ingenieros del Perú');
    $pdf->SetAuthor('Colegio de Ingenieros del Perú');
    $pdf->SetTitle('Préstamo de Equipos');
    $pdf->SetSubject('Préstamo de Equipos');


    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);


    $imagen = 'logo.png';
    $imagenTamano = 28; // Tamaño deseado para la miniatura (en píxeles)

    // Obtener el ancho y alto originales de la imagen
    list($imagenAncho, $imagenAlto) = getimagesize($imagen);

    // Calcular las dimensiones proporcionales para la miniatura
    $factorEscala = $imagenTamano / max($imagenAncho, $imagenAlto);
    $miniaturaAncho = $imagenAncho * $factorEscala;
    $miniaturaAlto = $imagenAlto * $factorEscala;

    // Calcular las coordenadas para imprimir la miniatura en la esquina superior derecha
    $posicionX = $pdf->getPageWidth() - $miniaturaAncho - 10; // 10 es el margen derecho
    $posicionY = 10; // 10 es el margen superior

    $pdf->Image($imagen, $posicionX, $posicionY, $miniaturaAncho, $miniaturaAlto);

    $pdf->SetXY(10, 20);
    $pdf->Cell(0, 10, 'Colegio de Ingenieros del Perú CIP ', 0, 1, 'C');
    $pdf->SetX(10);
    $pdf->Cell(0, 10, 'Préstamo de Equipos por el area de tecnologías deInformación', 0, 1, 'C');


    $pdf->Cell(0, 10, '', 'T', 1);

    // Agregar el contenido al PDF en una tabla
    $pdf->SetFont('helvetica', 'B', 11); // Fuente en negrita para los títulos de columna

    // Definir los títulos de columna
    $pdf->Cell(70, 10, 'Nombre del campo', 1, 0, 'C');
    $pdf->Cell(100, 10, 'Valor correspondiente', 1, 1, 'C');
    
    $pdf->SetFont('helvetica', '', 11); // Volver a la fuente normal para los valores de la tabla
    
    // Agregar los valores de cada campo
    $pdf->Cell(70, 10, 'Usuario que generó el préstamo', 1, 0, 'C');
    $pdf->Cell(100, 10, $NombreUser, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Fecha de Préstamo', 1, 0, 'C');
    $pdf->Cell(100, 10, $FechaPrestamo, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Fecha de Devolución', 1, 0, 'C');
    $pdf->Cell(100, 10, $FechaDevolucion, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Estado del Préstamo', 1, 0, 'C');
    $pdf->Cell(100, 10, $EstadoPrestamo, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Prestado a', 1, 0, 'C');
    $pdf->Cell(100, 10, $PrestamoPer, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Nombre del área', 1, 0, 'C');
    $pdf->Cell(100, 10, $NombreArea, 1, 1, 'C');
    
    $pdf->Cell(70, 10, 'Cantidad de equipos a prestar', 1, 0, 'C');
    $pdf->Cell(100, 10, $CantidadEquipos, 1, 1, 'C');

    $pdf->Cell(70, 10, 'Equipos prestados', 1, 0, 'C');
    $pdf->MultiCell(100, 10, $equiposPrestados, 1, 'C'); // Agregar una celda multilínea para mostrar la cadena de nombres


    $pdf->Ln(10);
    $posY = $pdf->GetY() + 2; // Ajustar dos espacios adicionales
    $pdf->SetXY(10, $posY);
    $pdf->SetFont('helvetica', '', 9); // Establecer fuente y tamaño de letra
    $pdf->MultiCell(0, 10, 'Recuerde que la fecha de devolución proporcionada es una estimación y puede estar sujeta a cambios. Una vez finalizado el préstamo, le solicitaremos ingresar la fecha de devolución real. Los equipos prestados estarán disponibles para futuros préstamos una vez que se haya completado el proceso de devolución correctamente.', 0, 'C');


    // Salida del PDF
    ob_end_clean(); // Limpiar el búfer de salida
    $pdf->Output('prestamo.pdf', 'I');

} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}

?>
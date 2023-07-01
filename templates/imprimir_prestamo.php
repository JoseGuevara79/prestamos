<?php
ob_start(); // Iniciar el búfer de salida
include_once "../configuraciones/bd.php";
include('../librerias/tcpdf/tcpdf.php');
include('../secciones/reporte_prestamo.php');
include('../secciones/prestamos_realizados.php');

// Obtener los datos del préstamo
// $IdPrestamo = $prestamo['IdPrestamo'];
$NombreUser = $prestamo['NombreUser'];
$FechaPrestamo = $prestamo['FechaPrestamo'];
$FechaDevolucion = $prestamo['FechaDevolucion'];
$EstadoPrestamo = $prestamo['EstadoPrestamo'] == 1 ? 'Activo' : 'Desactivado';
$PrestamoPer = $prestamo['prestamoper'];
$NombreArea = $prestamo['NombreArea'];
$CantidadEquipos = $prestamo['CantidadEquipos'];

// Crear instancia de TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Establecer información del documento
$pdf->SetCreator('Colegio de Ingenieros del Perú');
$pdf->SetAuthor('Colegio de Ingenieros del Perú');
$pdf->SetTitle('Préstamo de Equipos');
$pdf->SetSubject('Préstamo de Equipos');

// Agregar una página
$pdf->AddPage();

// Establecer la fuente y el tamaño del texto
$pdf->SetFont('helvetica', '', 12);

// Agregar la fecha actual en una esquina del documento
$pdf->SetXY(10, 10);
$pdf->Cell(0, 0, 'Fecha: ' . date('d/m/Y'), 0, 0, 'L');

$imagen = 'logo.png';
$imagenTamano = 28; // Tamaño deseado para la miniatura (en píxeles)

    
list($imagenAncho, $imagenAlto) = getimagesize($imagen);

$factorEscala = $imagenTamano / max($imagenAncho, $imagenAlto);
$miniaturaAncho = $imagenAncho * $factorEscala;
$miniaturaAlto = $imagenAlto * $factorEscala;
$posicionX = $pdf->getPageWidth() - $miniaturaAncho - 10; // 
$posicionY = 10; 

    $pdf->Image($imagen, $posicionX, $posicionY, $miniaturaAncho, $miniaturaAlto);
// Agregar el título y subtítulo del documento
$pdf->SetXY(10, 20);
$pdf->Cell(0, 10, 'Colegio de Ingenieros del Perú CIP ', 0, 1, 'C');
$pdf->SetX(10);
$pdf->Cell(0, 10, 'Préstamo de Equipos por el area de tecnologías deInformación', 0, 1, 'C');


$pdf->Cell(0, 10, '', 'T', 1);


// Agregar el contenido al PDF en una tabla
$pdf->SetXY(10, 50); // Posición inicial de la tabla
$pdf->SetFont('helvetica', 'B', 11); // Fuente en negrita para los títulos de columna

// Definir los títulos de columna
$pdf->Cell(70, 10, 'Nombre del campo', 1, 0, 'C');
$pdf->Cell(100, 10, 'Valor correspondiente', 1, 1, 'C');

$pdf->SetFont('helvetica', '', 11); // Volver a la fuente normal para los valores de la tabla

// Agregar los valores de cada campo
/* $pdf->Cell(70, 10, 'IdPrestamo', 1, 0, 'C');
$pdf->Cell(100, 10, $IdPrestamo, 1, 1, 'C'); */

$pdf->Cell(70, 10, 'Usuario que generó el prestamo', 1, 0, 'C');
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

// Agregar los nombres de los equipos prestados en una sola celda
$pdf->Cell(70, 10, 'Equipos prestados', 1, 0, 'C');
$equiposPrestados = array_column($equipos, 'NombreEquipo'); // Obtener solo los nombres de los equipos
$equiposPrestadosStr = implode(", ", $equiposPrestados); // Convertir los nombres en una cadena separada por comas
$pdf->MultiCell(100, 10, $equiposPrestadosStr, 1, 'C'); // Agregar una celda multilínea para mostrar la cadena de nombres


$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 11); // Fuente en negrita
$pdf->Cell(0, 10, 'Firma del Prestador: ______________________          Firma del Prestatario: ______________________', 0, 1, 'R');

$pdf->Ln(10);
$posY = $pdf->GetY() + 86; // Ajustar dos espacios adicionales
$pdf->SetXY(10, $posY);
$pdf->SetFont('helvetica', '', 10); // Establecer fuente y tamaño de letra
$pdf->MultiCell(0, 10, 'Recuerde que los equipos se le están prestando en buen estado y funcionando. Por favor, considere devolverlos en las mismas condiciones en las que los recibió. Cualquier daño o pérdida deberá ser reportado y será responsabilidad del prestatario. Gracias por su colaboración.', 0, 'L');
// Salida del PDF
ob_end_clean(); // Limpiar el búfer de salida
$pdf->Output('prestamo.pdf', 'I');

<?php
include_once "../configuraciones/bd.php";
$conexionBD = BD::crearInstancia();

$mesSeleccionado = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$anioSeleccionado = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$sql = "SELECT e.NombreEquipo AS Equipo, COUNT(*) AS Frecuencia
        FROM prestamos p
        INNER JOIN detalleprestamos dp ON p.IdPrestamo = dp.IdPrestamo
        INNER JOIN equipos e ON dp.IdEquipo = e.IdEquipo
        WHERE MONTH(p.FechaPrestamo) = :mesSeleccionado AND YEAR(p.FechaPrestamo) = :anioSeleccionado
        GROUP BY dp.IdEquipo";

$consulta = $conexionBD->prepare($sql);
$consulta->bindParam(':mesSeleccionado', $mesSeleccionado);
$consulta->bindParam(':anioSeleccionado', $anioSeleccionado);
$consulta->execute();

$equipos = [];
$frecuencias = [];

$sqlPrestamosActivos = "SELECT COUNT(*) AS Cantidad
                        FROM prestamos
                        WHERE MONTH(FechaPrestamo) = :mesSeleccionado AND YEAR(FechaPrestamo) = :anioSeleccionado AND EstadoPrestamo = 1";

$consultaPrestamosActivos = $conexionBD->prepare($sqlPrestamosActivos);
$consultaPrestamosActivos->bindParam(':mesSeleccionado', $mesSeleccionado);
$consultaPrestamosActivos->bindParam(':anioSeleccionado', $anioSeleccionado);
$consultaPrestamosActivos->execute();
$prestamosActivos = $consultaPrestamosActivos->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener la cantidad de préstamos desactivos por mes
$sqlPrestamosDesactivos = "SELECT COUNT(*) AS Cantidad
                           FROM prestamos
                           WHERE MONTH(FechaPrestamo) = :mesSeleccionado AND YEAR(FechaPrestamo) = :anioSeleccionado AND EstadoPrestamo = 0";

$consultaPrestamosDesactivos = $conexionBD->prepare($sqlPrestamosDesactivos);
$consultaPrestamosDesactivos->bindParam(':mesSeleccionado', $mesSeleccionado);
$consultaPrestamosDesactivos->bindParam(':anioSeleccionado', $anioSeleccionado);
$consultaPrestamosDesactivos->execute();

$prestamosDesactivos = $consultaPrestamosDesactivos->fetch(PDO::FETCH_ASSOC);

if ($consulta->rowCount() > 0) {
    while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
        $equipos[] = $fila["Equipo"];
        $frecuencias[] = $fila["Frecuencia"];
    }
}

function obtenerNombreMes($numeroMes) {
    $nombresMeses = array(
        1 => "Enero",
        2 => "Febrero",
        3 => "Marzo",
        4 => "Abril",
        5 => "Mayo",
        6 => "Junio",
        7 => "Julio",
        8 => "Agosto",
        9 => "Septiembre",
        10 => "Octubre",
        11 => "Noviembre",
        12 => "Diciembre"
    );

    return $nombresMeses[$numeroMes];
}
$prestamosActivos = $prestamosActivos['Cantidad'];
$prestamosInactivos = $prestamosDesactivos['Cantidad'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Consulta</title> 
    <style>
        #grafico {
            max-width: 400px; /* Aumenta el ancho máximo del gráfico de barras */
            margin: 0 auto; /* Centra el gráfico */
            display: flex;
            justify-content: center;
        }
        .chart-container {
            margin: 30px;
            flex: 1;
        }
        #grafico-barras {
        height: 300px; /* Ajusta la altura del gráfico de barras */
    }
        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        form label {
            margin-right: 10px;
        }
        form select {
            margin-right: 20px;
        }
        form input[type="submit"] {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="get" action="">
        <label for="mes">Seleccione mes:</label>
        <select name="mes" id="mes">
            <?php for ($mes = 1; $mes <= 12; $mes++) { ?>
                <option value="<?php echo $mes; ?>" <?php if ($mes == $mesSeleccionado) echo "selected"; ?>><?php echo obtenerNombreMes($mes); ?></option>
            <?php } ?>
        </select>
        <label for="anio">Seleccione año:</label>
        <select name="anio" id="anio">
            <?php for ($anio = date('Y'); $anio >= 2000; $anio--) { ?>
                <option value="<?php echo $anio; ?>" <?php if ($anio == $anioSeleccionado) echo "selected"; ?>><?php echo $anio; ?></option>
            <?php } ?>
        </select>
        <input type="submit" value="Consultar">
    </form>
    <div id="grafico">
        <div class="chart-container">
            <canvas id="grafico-barras"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="grafico-circular"></canvas>
        </div>
    </div>

    <script src="../node_modules/chart.js/dist/chart.umd.js"></script>
    <script>
    // Obtener los datos del gráfico desde PHP
    var equipos = <?php echo json_encode($equipos); ?>;
    var frecuencias = <?php echo json_encode($frecuencias); ?>;
    var prestamosActivos = <?php echo $prestamosActivos; ?>;
    var prestamosInactivos = <?php echo $prestamosInactivos; ?>;

    // Configuración del gráfico de barras
    var configBarras = {
        type: 'bar',
        data: {
            labels: equipos,
            datasets: [{
                label: 'Frecuencia de equipos prestados',
                data: frecuencias,
                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 205, 86, 0.5)', 'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 205, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }
        }
    };
    // Configuración del gráfico circular
    var configCircular = {
            type: 'doughnut',
            data: {
                labels: ['Activos', 'Terminados'],
                datasets: [{
                    data: [prestamosActivos, prestamosInactivos],
                    backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        };

        // Crear gráfico de barras
        var ctxBarras = document.getElementById('grafico-barras').getContext('2d');
        new Chart(ctxBarras, configBarras);

        // Crear gráfico circular
        var ctxCircular = document.getElementById('grafico-circular').getContext('2d');
        new Chart(ctxCircular, configCircular);
        </script>
</body>
</html>
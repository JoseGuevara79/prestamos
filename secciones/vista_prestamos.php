<?php include('../templates/cabecera.php'); ?>
<?php include('../secciones/prestamos.php'); ?>
<link rel="stylesheet" href="../estilos/bootstrap.min.css">
<div class="row">
    <div class="col-12">
        <br/>
        <div class="row">
        <div class="col-4">
        <form action="" method="post">
        <div class="card">
            <div class="card-header">Prestamos</div>
            <div class="card-body">
                <div class="mb-3">
                  <label for="" class="form-label">Fecha de prestamo</label>
                  <input type="date"
                    class="form-control" 
                    name="fecha_prestamo" 
                    id="fecha_prestamo" 
                    value="<?php echo isset($_SESSION['prestamo']['FechaPrestamo']) ? $_SESSION['prestamo']['FechaPrestamo'] : ''; ?>"
                    aria-describedby="helpId" placeholder="" required>
                </div>
                <div class="mb-3">
                  <label for="" class="form-label">Fecha devolucion aproximada</label>
                  <input type="date"
                    class="form-control" 
                    name="fecha_devolucion" 
                    id="fecha_devolucion" 
                    value="<?php echo isset($_SESSION['prestamo']['FechaDevolucion']) ? $_SESSION['prestamo']['FechaDevolucion'] : ''; ?>"
                    aria-describedby="helpId" placeholder="" required>
                </div>
                <div class="mb-3">
                  <label for="" class="form-label">Nombre del prestatario</label>
                  <input type="text"
                    class="form-control" 
                    name="nombre_persona" 
                    id="nombre_persona" 
                    value="<?php echo isset($_SESSION['prestamo']['prestamoper']) ? $_SESSION['prestamo']['prestamoper'] : ''; ?>"
                    aria-describedby="helpId" placeholder="Nombre de la persona, prestatario" required>
                </div>
                
                <div class="mb-3">
                    <label for="" class="form-label">Area</label>
                    <select class="form-control" name="areas[]" id="listaAreas" onchange="actualizarAreaSeleccionada()" required>
                        <option>Seleccione una opción</option>
                        <?php
                        $areasSeleccionadas = isset($_SESSION['prestamo']['IdArea']) ? $_SESSION['prestamo']['IdArea'] : array();
                        foreach ($listaAreas as $area) {
                            $selected = in_array($area['IdArea'], $areasSeleccionadas) ? 'selected' : '';
                            echo '<option value="' . $area['IdArea'] . '" ' . $selected . '>' . $area['NombreArea'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <script>
                    function actualizarAreaSeleccionada() {
                        var selectAreas = document.getElementById("listaAreas");
                        var areaSeleccionada = selectAreas.options[selectAreas.selectedIndex].textContent;
                        selectAreas.options[selectAreas.selectedIndex].text = areaSeleccionada;
                    }
                </script>
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" value="selecciona" class="btn btn-info">Seleccionar</button> 
                </div>   
                <BR></BR>
                <div class="mb-3">
                    <label for="" class="form-label">Equipos</label>
                    <select class="form-control" name="equipos[]" id="listaEquipos" onchange="actualizarEquipoSeleccionado()" required>
                        <option>Seleccione una opción</option>
                        <?php
                        $equiposSeleccionados = isset($_SESSION['prestamo']['IdEquipo']) ? $_SESSION['prestamo']['IdEquipo'] : array(); 
                        foreach ($listaEquipos as $equipo) {
                            $selected = in_array($equipo['IdEquipo'], $equiposSeleccionados) ? 'selected' : '';
                            echo '<option value="' . $equipo['IdEquipo'] . '" ' . $selected . '>' . $equipo['NombreEquipo'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <script>
                    function actualizarEquipoSeleccionado() {
                        var selectEquipos = document.getElementById("listaEquipos");
                        var EquipoSeleccionado = selectEquipos.options[selectEquipos.selectedIndex].textContent;
                        selectEquipos.options[selectEquipos.selectedIndex].text = EquipoSeleccionado;
                    }
                </script>

                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" value="agregar" class="btn btn-success">Agregar Equipos</button>   
                </div>
                
            </div>
        </div>
        </form>
     </div>
    <div class="col-8">
        <div class="cards">
            <div class="card-header">Datos prestamo</div>
            <div class="card-body">
                <table class="table">
                <thead>
                    <tr>
                    <th>Fecha inicio de prestamo</th>
                    <th>Fecha devolucion aproximada</th>
                    <th>PrestamoPersona</th>
                    <th>Area de prestamo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo isset($_SESSION['prestamo']['FechaPrestamo']) ? $_SESSION['prestamo']['FechaPrestamo'] : ''; ?></td>
                        <td><?php echo isset($_SESSION['prestamo']['FechaDevolucion']) ? $_SESSION['prestamo']['FechaDevolucion'] : ''; ?></td>
                        <td><?php echo isset($_SESSION['prestamo']['prestamoper']) ? $_SESSION['prestamo']['prestamoper'] : ''; ?></td>
                        <td>
                            <?php 
                            if (isset($_SESSION['prestamo']['IdArea'])) {
                                $areasSeleccionadas = $_SESSION['prestamo']['IdArea'];
                                foreach ($areasSeleccionadas as $areaId) {
                                    echo obtenerNombreArea($areaId) . "<br>";
                                }
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>

        <div class="cards">
            <div class="card-header">Equipos a prestar</div>
            <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>    
                        <th>Equipos</th>
                        <th>Acciones</th>
                    </tr>
                </thead> 
                <tbody>
                    <?php
                    if (isset($_SESSION['prestamo']) && is_array($_SESSION['prestamo'])) {
                        $contador = 1;
                        foreach ($_SESSION['prestamo'] as $id => $prestamo) {
                            if ($id !== 'FechaPrestamo' && $id !== 'FechaDevolucion' && $id !== 'IdArea') {
                                if (isset($prestamo['IdEquipo']) && is_array($prestamo['IdEquipo']) && !empty($prestamo['IdEquipo'])) {
                                    ?>
                                    <tr>
                                        <td><?php echo str_pad($contador, 2, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <?php
                                            foreach ($prestamo['IdEquipo'] as $equipoId) {
                                                echo obtenerNombreEquipo($equipoId) . "<br>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <form action="" method="post">
                                                <input type="hidden" name="contador" value="<?php echo $id; ?>" />
                                                <button type="submit" name="accion" value="borrar" class="btn btn-danger">Borrar</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                    $contador++;
                                }
                            }
                        }
                    }
                    ?>
                </tbody>

            </table>
            </div>
        </div>  

            <div class="cards">
              <div class="card-header">DetallePrestamos</div>
              <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Total de equipos a prestar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                $totalEquipos = isset($_SESSION['prestamo']) ? count($_SESSION['prestamo']) - 4 : 0;
                                echo str_pad($totalEquipos, 1, '0', STR_PAD_LEFT);
                                ?>
                            </td>  
                            <td>
                                <form action="" method="post">
                                    <button type="submit" name="accion" value="realizarprestamo" class="btn btn-primary">Generar préstamo</button>
                                    <button type="submit" name="accion" value="cancelar" class="btn btn-danger">Cancelar</button>
                                </form>
                            </td>


                        </tr>
                    </tbody>
                </table>
              </div>
            </div>
        </div>  
</div>
</div>
<?php include('../templates/pie.php'); ?>
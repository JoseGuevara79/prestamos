<?php include('../templates/cabecera.php'); ?>
<?php include('../secciones/equipos.php'); ?>
<link rel="stylesheet" href="../estilos/bootstrap.min.css">
<style>
    .form-control:invalid {
        border-color: red;
    }
</style>
<div class="row">
    <div class="col-12">
        <br/>
        <div class="row">

        <div class="col-4">
        <form action="" method="post">
         
        <div class="card">
            <div class="card-header">Equipos</div>
            <div class="card-body">
                <div class="mb-3">

                <label for="id" class="form-label">ID</label>
                <input type="text"
                    class="form-control" 
                    name="id" 
                    id="id" 
                    value="<?php echo $ide ?>"
                    aria-describedby="helpId" placeholder="ID">
                </div>
                <div class="mb-3">
                  <label for="nombre_equipo" class="form-label">Nombre*</label>
                  <input type="text"
                    class="form-control" 
                    name="nombre_equipo" 
                    id="nombre_equipo" 
                    value="<?php echo $nombre_equipos ?>"
                    aria-describedby="helpId" placeholder="Nombre de equipo" required>
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Categoría</label>
                    <select class="form-control" name="categorias[]" id="listaCategorias" onchange="actualizarCategoriaSeleccionada()" required>
                        <option>Seleccione una opción</option>
                        <?php
                        $id_categoria = isset($id_categoria) ? $id_categoria : null; 
                        foreach ($listaCategorias as $categoria) {
                            ?>
                            <option value="<?php echo $categoria['IdCategoria']; ?>" <?php if ($categoria['IdCategoria'] == $id_categoria) echo "selected"; ?>><?php echo $categoria['NombreCategoria']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <script>
                    function actualizarCategoriaSeleccionada() {
                        var selectCategorias = document.getElementById("listaCategorias");
                        var categoriaSeleccionada = selectCategorias.options[selectCategorias.selectedIndex].textContent;
                        selectCategorias.options[selectCategorias.selectedIndex].text = categoriaSeleccionada;
                    }
                </script>

                <div class="mb-3">
                  <label for="marca_equipo" class="form-label">Marca</label>
                  <input type="text"
                    class="form-control" 
                    name="marca_equipo" 
                    id="marca_equipo" 
                    value="<?php echo $marca ?>"
                    aria-describedby="helpId" placeholder="Marca del equipo" required>
                </div>
                <div class="mb-3">
                  <label for="num_serie" class="form-label">NumeroSerie</label>
                  <input type="text"
                    class="form-control" 
                    name="num_serie" 
                    id="num_serie" 
                    value="<?php echo $numeroserie ?>"
                    aria-describedby="helpId" placeholder="Numero de serie">
                </div>
                <div class="mb-3">
                  <label for="modelo_equipo" class="form-label">Modelo</label>
                  <input type="text"
                    class="form-control" 
                    name="modelo_equipo" 
                    id="modelo_equipo" 
                    value="<?php echo $modelo ?>"
                    aria-describedby="helpId" placeholder="Modelo del equipo">
                </div>
                <div class="mb-3">
                  <label for="descripcion" class="form-label">Descripcion</label>
                  <input type="text"
                    class="form-control" 
                    name="descripcion" 
                    id="descripcion" 
                    value="<?php echo $description ?>"
                    aria-describedby="helpId" placeholder="Descripcion del equipo" required>
                </div>
                
                <div class="mb-3">
                    <label for="disponibilidad" class="form-label">Disponibilidad</label>
                    <input type="number"
                        class="form-control" 
                        name="disponibilidad" 
                        id="disponibilidad" 
                        min="0" 
                        max="1" 
                        onkeypress="return event.charCode === 48 || event.charCode === 49"
                        value="<?php echo $disponible ?>"
                        aria-describedby="helpId" 
                        placeholder="1=Activo 0=Inactivo" required>
                    <small id="helpId" class="form-text text-muted">Seleccione 1 para Disponible y 0 para No disponible.</small>
                </div>
                <div class="btn-group d-flex flex-wrap" role="group" aria-label="">
                    <button type="submit" name="accion" value="agregar" class="btn btn-sm btn-success">Agregar</button>
                    <button type="submit" name="accion" value="editar" class="btn btn-sm btn-info">Actualizar</button>
                    <button type="submit" name="accion" value="borrar" class="btn btn-sm btn-danger">Borrar</button>
                    <button type="submit" name="accion" value="cancelar" class="btn btn-sm btn-light">Cancelar</button>
                </div>
            
            </div> 
        </div> 
        </form>

        </div>

        <div class="col-8">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Número de Serie</th>
                    <th>Modelo</th>
                    <th>Descripción</th>
                    <th>Disponibilidad</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                
                <?php foreach ($listaEquipos as $equipo) { ?>
                    <tr>
                        <td><?php echo $equipo['IdEquipo']; ?></td>
                        <td><?php echo $equipo['NombreEquipo']; ?></td>
                        <td>
                            <?php
                            $id_categoria = $equipo['IdCategoria'];
                            $nombre_categoria = '';
                            if ($id_categoria) {
                                $consultaCategoria = $conexionBD->prepare("SELECT NombreCategoria FROM categorias WHERE IdCategoria = ?");
                                $consultaCategoria->execute([$id_categoria]);
                                $categoria = $consultaCategoria->fetch();
                                $nombre_categoria = $categoria ? $categoria['NombreCategoria'] : '';
                            }
                            echo $nombre_categoria;
                            ?>
                        </td>
                        <td><?php echo $equipo['Marca']; ?></td>
                        <td><?php echo $equipo['NumSerie']; ?></td>
                        <td><?php echo $equipo['Modelo']; ?></td>
                        <td><?php echo $equipo['Descripcion']; ?></td>
                        <td><?php echo $equipo['Disponibilidad']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="id" id="id" value="<?php echo $equipo['IdEquipo']; ?>" />
                                <input type="submit" value="seleccionar" name="accion" class="btn btn-info">
                            </form>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
        </div>


</div>
</div>
<?php include('../templates/pie.php'); ?>
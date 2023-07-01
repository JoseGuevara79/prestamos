<?php include('../templates/cabecera.php'); ?>
<?php include('../secciones/areas.php'); ?>
<link rel="stylesheet" href="../estilos/bootstrap.min.css">
<div class="row">
    <div class="col-12">
        <br/>
        <div class="row">

        <div class="col-5">
        <form action="" method="post">
        <div class="card">
            <div class="card-header">Areas</div>
            <div class="card-body">
            <div class="mb-3">
                  <label for="id" class="form-label">ID</label>
                  <input type="text"
                  class="form-control"
                  name="id" 
                  id="id"
                  value="<?php echo $ida ?>"  
                  aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="nombre_area" class="form-label">Nombre</label>
                <input type="text"
                    class="form-control" 
                    name="nombre_area" 
                    id="nombre_area" 
                    value="<?php echo $nombre_areas ?>" 
                    aria-describedby="helpId" placeholder="Nombre de area" required>
            </div>
            <div class="mb-3">
                <label for="localizacion" class="form-label">Localizacion</label>
                <input type="text"
                    class="form-control" 
                    name="localizacion" 
                    id="localizacion" 
                    value="<?php echo $localizacion ?>" 
                    aria-describedby="helpId" placeholder="Lugar donde se ubica" required>
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

        <div class="col-7">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Localizacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($listaAreas as $area){ ?>
                    <tr>
                        <td> <?php echo $area['IdArea']; ?> </td>
                        <td> <?php echo $area['NombreArea']; ?> </td>
                        <td> <?php echo $area ['Localizacion']; ?> </td>
                        <td>
                        <form action="" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo $area ['IdArea']; ?>" />
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
<?php include('../templates/pie.php'); ?>
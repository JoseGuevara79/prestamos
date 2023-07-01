<?php include('../templates/cabecera.php'); ?>
<?php include('../secciones/categorias.php'); ?>

<link rel="stylesheet" href="../estilos/bootstrap.min.css">

<div class="row">
    <div class="col-12">
        <br/>
        <div class="row">

        <div class="col-5">
        <form action="" method="post">
        <div class="card">
            <div class="card-header">Categorias</div>
            <div class="card-body">
            <div class="mb-3">
                <label for="" class="form-label">ID</label>
                <input type="text"
                        class="form-control" 
                        name="id" 
                        id="id" 
                        value="<?php echo $id ?>"
                        aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="nombre_categoria" class="form-label">Nombre</label>
                <input type="text"
                        class="form-control" 
                        name="nombre_categoria" 
                        id="nombre_categoria" 
                        value="<?php echo $nombre_categoria ?>"
                        aria-describedby="helpId" placeholder="Nombre de la categoria" required>
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($listaCategorias as $categoria){ ?>
                <tr>
                    <td> <?php echo $categoria['IdCategoria']; ?> </td>
                    <td> <?php echo $categoria['NombreCategoria']; ?> </td>
                    <td>
                    <form action="" method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo $categoria ['IdCategoria']; ?>" />
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
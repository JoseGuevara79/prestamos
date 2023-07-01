<?php include('../templates/cabecera.php'); ?>
<?php include('../secciones/usuarios.php'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="../estilos/bootstrap.min.css">
<div class="row">
    <div class="col-12">
        <br/>
        <div class="row">

        <div class="col-5">
        <form action="" method="post">
        <div class="card">
            <div class="card-header">Usuarios</div>
            <div class="card-body">

            <div class="mb-3">
                  <label for="id" class="form-label">ID</label>
                  <input type="text"
                  class="form-control"
                  name="id" 
                  id="id"
                  value="<?php echo $idu ?>"  
                  aria-describedby="helpId" placeholder="ID">
            </div>
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">NombreUsuario</label>
                <input type="text"
                    class="form-control" 
                    name="nombre_usuario" 
                    id="nombre_usuario" 
                    value="<?php echo $nombre_usuarios ?>" 
                    aria-describedby="helpId" placeholder="Nombre de usuario" required>
            </div>
            <div class="mb-3">
                <label for="contrase" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password"
                        class="form-control" 
                        name="contrase" 
                        id="contrase" 
                        value="<?php echo $contras ?>" 
                        aria-describedby="helpId" placeholder="Contraseña" required>
                    <button class="btn btn-outline-secondary" type="button" id="ver-contrasenia">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const btnVerContrasenia = document.getElementById("ver-contrasenia");
                    const inputContrasenia = document.getElementById("contrase");

                    btnVerContrasenia.addEventListener("click", function() {
                        if (inputContrasenia.type === "password") {
                            inputContrasenia.type = "text";
                        } else {
                            inputContrasenia.type = "password";
                        }
                    });
                });
            </script>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="text"
                    class="form-control" 
                    name="correo" 
                    id="correo" 
                    value="<?php echo $email?>" 
                    aria-describedby="helpId" placeholder="Correo" required>
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
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre Usuario</th>
                        <th>Password</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($listaUsuarios as $usuario){ ?>
                    <tr>
                        <td> <?php echo $usuario['IdUser']; ?> </td>
                        <td> <?php echo $usuario['NombreUser']; ?> </td>
                        <td>
                            <input type="hidden" name="contras_bd[]" value="<?php echo $usuario['Password']; ?>" />
                            ********
                        </td>
                        <td> <?php echo $usuario ['Correo']; ?> </td>
                        <td>
                        <form action="" method="post">
                            <input type="hidden" name="id" id="id" value="<?php echo $usuario ['IdUser']; ?>" />
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
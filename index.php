
<!doctype html>
<html lang="en">

<head>
  <title>PrestamosEquiposCIP</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link rel="stylesheet" href="./estilos/bootstrap.min.css">
  <link rel="stylesheet" href="./estilos/complementos.css">
</head>
<body>
   <div class="container">
    <div class="row">
      <div class="col-md-4 ">
      </div>
      <div class="col-md-4 ">
      <br>
      <br>
      <form action="iniciar_sesion.php" method=post>
          <div class="card">
          <div class="card-header text-center">
            <h4 class="mb-0">Inicio de sesión CIP</h4>
            <h6 class="mb-0">SISTEMA DE PRÉSTAMOS</h6>
            <br>
            <img src="./templates/logo.png" alt="Logo" class="img-thumbnail logo-thumbnail">
          </div>
              <div class="card-body">
                  
                  <div class="mb-3">
                    <label for="" class="form-label">Usuario</label>
                    <input type="text"
                      class="form-control" 
                      name="usuario" 
                      id="usuario" 
                      aria-describedby="helpId" placeholder="usuario">

                    <small id="helpId" class="form-text text-muted">Escriba su usuario</small>
                  </div>

                  <div class="mb-3">
                    <label for="" class="form-label">Contraseña</label>
                    <input type="password"
                      class="form-control" 
                      name="contrasenia" 
                      id="contrasenia" 
                      aria-describedby="helpId" placeholder="Password">
                    <small id="helpId" class="form-text text-muted">Escriba su contraseña</small>
                  </div>

                  <div class="text-center">
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                  </div>
              </div>
              </form>
          </div>
          <?php
        // Verificar si hay un mensaje de error en la URL
        if (isset($_GET['mensaje'])) {
            $mensaje = $_GET['mensaje'];
            // Mostrar la alerta de error
            echo '<div class="alert alert-danger">' . $mensaje . '</div>';
        }
        ?>
    </div>
   </div>
   
  <header>
    <!-- place navbar here -->
  </header>
  <main>

  </main>
  <footer>
    <!-- place footer here -->
  </footer>
  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>
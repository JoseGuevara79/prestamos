<?php include('../templates/cabecera.php'); ?>

<link rel="stylesheet" href="../estilos/complementos.css">

<div class="cards text-center mx-auto w-50 mt-4">
  <div class="card-body">
    <h5 class="card-title" style="font-family: Arial, sans-serif;">
      <img src="../templates/logo.png" alt="Logo" class="img-thumbnail logo-thumbnail">
      <br>
      <?php
      session_start();

      if (isset($_SESSION['usuario'])) {
          echo "<span style='font-weight: bold;'>Bienvenido:</span> " . $_SESSION['usuario']['NombreUser'];
      } else {
          echo "No se ha iniciado sesión.";
      }
      
      ?>
    </h5>
    <p class="card-text" style="font-family: Arial, sans-serif;">
      <?php
      if (isset($_SESSION['correo'])) {
          echo "<span style='font-weight: bold;'>Correo:</span> " . $_SESSION['correo'];
      }
      ?>
    </p>
    <a class="btns" href="vista_usuarios.php">Ver usuarios</a>
  </div>
</div>


<?php include('total_equipos.php'); ?>
<?php include('graficos.php'); ?>
<?php include('../templates/pie.php'); ?>




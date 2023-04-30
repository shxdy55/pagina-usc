<?php 
session_start();
    if(!isset($_SESSION['usuario'])){
    header("Location:../index.php");
}else{
    if($_SESSION['usuario']=="ok"){
        $nombreUsuario=$_SESSION["nombreUsuario"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio web</title>

<link rel="stylesheet" href="./css/bootstrap.min.css" />

</head>
<body>
          

         <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
             <ul class="nav navbar-nav">
                 <li class="nav-item">
                     <a class="nav-link" href="#">Tienda santiaguina </a>           
                 </li>

                 <li class="nav-item">
                     <a class="nav-link" href="index.php">Inicio</a>
                 </li>

                 <li class="nav-item">
                     <a class="nav-link" href="productos.php">Ropa</a>
                 </li>

                 <li class="nav-item">
                     <a class="nav-link" href="nosotros.php">Nosotros</a>
                 </li>

             </ul>
         </nav>

         <div class="container">
         </br>
             <div class="row">
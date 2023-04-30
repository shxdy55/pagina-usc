<?php include("../template/cabecera.php"); ?>
<?php


$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");



switch($accion){

    case "Agregar":

       //INSERT INTO `ropa` (`id`, `nombre`, `imagen`) VALUES (NULL, 'ropa.php', 'imagen.jpg');
       $sentenciaSQL = $conexion->prepare("INSERT INTO ropa (nombre, imagen) VALUES (:nombre,:imagen);");
       $sentenciaSQL->bindParam(':nombre', $txtNombre);

        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen!=""){
            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);
        }

       $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
       $sentenciaSQL->execute();

       header("Location:productos.php");
        break;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE ropa SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        if($txtImagen!=""){

            $fecha = new DateTime();
            $nombreArchivo = ($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../img/".$nombreArchivo);


            $sentenciaSQL = $conexion->prepare("SELECT imagen FROM ropa WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $ropa = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if( isset($ropa["imagen"]) &&($ropa["imagn"]!="imagen.jpg") ){
                if(file_exists("../../img/".$ropa["imagen"])){
                    unlink("../../img/".$ropa["imagen"]);
                }
            }

            $sentenciaSQL = $conexion->prepare("UPDATE ropa SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
        }

        //echo "Presionado boton Modificar";
        header("Location:productos.php");
        break;

    case "Cancelar":
        header("Location:productos.php");
         break;  

     case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM ropa WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $ropa = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        $txtNombre=$ropa['nombre'];
        $txtImagen=$ropa['imagen'];
        //echo "Presionado boton Seleccionar";
        break;

    case "Borrar":
        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM ropa WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $ropa = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if( isset($ropa["imagen"]) &&($ropa["imagn"]!="imagen.jpg") ){
            if(file_exists("../../img/".$ropa["imagen"])){
                unlink("../../img/".$ropa["imagen"]);
            }
        }

        $sentenciaSQL = $conexion->prepare("DELETE FROM ropa WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        
        //echo "Presionado boton Borrar";
        header("Location:productos.php");
        break;    
}

    $sentenciaSQL = $conexion->prepare("SELECT * FROM ropa");
    $sentenciaSQL->execute();
    $listaRopa = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);



?>

<div class="col-md-5">

<div class="card">
    <div class="card-header">
        Datos de Ropa 
    </div>
    <div class="card-body">

    <form method="POST" enctype="multipart/form-data" >

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" required readonly class="form-control" value= "<?php echo $txtID;?>" name="txtID" id="txtID" placeholder="ID">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre:</label>
    <input type="text" required class="form-control" value= "<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre" placeholder="Nombre de la prenda">
    </div>

    <div class = "form-group">
    <label for="txtNombre">Imagen:</label>

    <br/>
    
    <?php if($txtImagen!=""){ ?>
        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; ?>" width="100" alt="" srcset="">
        
    <?php }?>


    <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Nombre del libro">
    </div>



    <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
    </div>

    </form>
        
    </div>
    
</div>

    
    
    
</div>
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaRopa as $ropa) { ?>
            <tr>
                <td><?php echo $ropa['id']; ?></td>
                <td><?php echo $ropa['nombre']; ?></td>
                <td>

                <img class="img-thumbnail rounded" src="../../img/<?php echo $ropa['imagen']; ?>" width="100" alt="" srcset="">


                </td>

                <td>
                <form method="post">

                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $ropa['id']; ?>"/>
                   
                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include("../template/pie.php"); ?>
<?php
session_start();

require_once('../../database/database.php');
// include('../../includes/sesion_validar.php');
$conexion = new database;
$con = $conexion->conectar();
?>
<!DOCTYPE html>
<html lang="en">
<script>
        function centrar(){
            iz =(screen.width-document.body.clientWidth) / 2;
            der = (screen.height-document.body.clientHeight) / 3;
            moveTo(iz,der);
        }
    </script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDICION DE OBJETOS</title>
    <link rel="stylesheet" href="css/ventana_objetos_usu.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body onload="centrar()">



        <!-- AQUI  ESTA LA CONSULTA A LA BASE DE DATOS ACERCA DEL OBJETO -->
         <?php 

            try{
                $id_serial = $_GET['id'];
                $_SESSION['objeto_buscado'] =$id_serial;
                $objetos_1 = $con->prepare("SELECT * FROM objetos 
                 INNER JOIN marca ON objetos.id_marca = marca.id_marca
                 INNER JOIN estado ON objetos.id_estado = estado.id_estado
                 WHERE id_serial = '$_SESSION[objeto_buscado]'");
                $objetos_1 ->execute();
    
                $consulta_1 = $objetos_1->fetch();
                
            }catch(PDOException){
               
                $objetos_1 = $con->prepare("SELECT * FROM objetos 
                 INNER JOIN marca ON objetos.id_marca = marca.id_marca
                 INNER JOIN estado ON objetos.id_estado = estado.id_estado
                 WHERE id_serial = '$_SESSION[objeto_buscado]'");
                $objetos_1 ->execute();
    
                $consulta_1 = $objetos_1->fetch();
            }
           
          


         
         
         ?>

 <main class="formulario">

        <form action="../../uploads/upload_objetos.php" method ="POST" enctype="multipart/form-data" >

            <img src="../../uploads/<?php echo $consulta_1['foto'] ?>" alt="" class="imagen" id="imagen_objeto">



            <div class="image_options"> 

                
            
                <button class="basura" onclick="" id="Borrar foto"><i class="bi bi-trash-fill"></i></button>
                <input type="submit" class="enviar_foto" id ="enviar_foto" name = "submit"  placeholder="ACTUALIZAR FOTO">
                <label for="fileUpload" class="label_file_input"> <i class="bi bi-upload"></i></label>
                <input type="file" class="file_input" id ="fileUpload"  name="foto" hidden>
                <input type="hidden" name="serial" value="<?php echo $consulta_1['id_serial']; ?>">


            </div>

        </form>

        <form action="" method="POST" enctype="multipart/form-data" class="datos_basicos" >

            <label for="name" class="label_titulo">Serial</label>
            <input type="text" id="name" name ="serial_nuevo" class="data_input" placeholder="nombre" value="<?php echo $consulta_1['id_serial'] ?>">

            <label for="" class="label_titulo">Nombre</label>
            <input type="text" name ="nombre_nuevo" class="data_input" value="<?php echo $consulta_1['nombre'] ?>">

            <label for="" class="label_titulo">Caracteristicas</label>
            <textarea class="data_input caracteristica" name ="carac_nueva" placeholder="Escribe aquí..."><?php echo $consulta_1['caracteristica'] ?></textarea>

            <label for="" class="label_titulo">Marca</label>
                <select name="marca_nueva" id="" class="selectos" >
                    
                    
                        <option value="<?php echo $consulta_1['id_marca'] ?> ">
                         ACTUAL: <?php echo $consulta_1['nombre_marca'] ?>
                        </option>

                        <?php  
                        $sql = $con->prepare("SELECT * FROM marca WHERE id_marca != '$consulta_1[id_marca]'");
                        $sql->execute();

                        $consulta_2 = $sql->fetchAll();

                        foreach($consulta_2 as $marca){ ?>

                            <option value="<?php echo $marca['id_marca']?>">  <?php echo $marca['nombre_marca']?> </option>


                            <?php  } ?>


                </select>
                
            <label for="estado" class="label_titulo">Estado</label>
                <select name="estado_nuevo" id="" class="selectos">
                    <option value="<?php echo $consulta_1['id_estado'] ?>"> 
                        ACTUAL:
                        <?php echo $consulta_1['estado'] ?>
                    </option>

                    <?php  
                        $sql_2 = $con->prepare("SELECT * FROM estado WHERE id_estado != '$consulta_1[id_estado]'");
                        $sql_2->execute();

                        $consulta_3 = $sql_2->fetchAll();
                    
                        foreach($consulta_3 as $estados){
                    ?>
                    

                    <option value="<?php  echo $estados['id_estado'] ?>"> <?php  echo $estados['estado'] ?></option>
                    <?php } ?>
                </select>

            <input type="submit" name="enviar">
            
        </form>

</main> 

<?php if(isset($_POST['enviar'])){

           $id_serial = $_POST['serial_nuevo'];
           $serial_anterior = $_POST['serial_nuevo'];
           $nombre_objeto =$_POST['nombre_nuevo'];
           $caracteristica =$_POST['carac_nueva'];
           $marca = $_POST['marca_nueva'];
           $estado =$_POST['estado_nuevo'];

        
            $cambiar_datos = $con ->prepare("UPDATE objetos SET id_serial = :serial_objeto, nombre = :nombre, caracteristica =  :caract, id_marca = :marca, id_estado = :estado WHERE id_serial = :serial_anterior");
            $cambiar_datos->bindParam(':serial_objeto', $id_serial, PDO::PARAM_STR);
            $cambiar_datos->bindParam(':nombre', $nombre_objeto, PDO::PARAM_STR);
            $cambiar_datos->bindParam(':serial_anterior', $serial_anterior, PDO::PARAM_STR);
            $cambiar_datos->bindParam(':caract', $caracteristica, PDO::PARAM_STR);
            $cambiar_datos->bindParam(':marca', $marca, PDO::PARAM_STR);
            $cambiar_datos->bindParam(':estado', $estado, PDO::PARAM_STR);
            $cambiar_datos ->execute();

          
            echo  "<script>window.close()</script>";
            exit();
        }
    
    
    
    
    ?>
<script>

        const imagen = document.getElementById('imagen_objeto');
        const input = document.getElementById('fileUpload')

        input.addEventListener("change", (e) =>{

            let file = e.target.files[0];

            if (file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    imagen.src = e.target.result;
                };

                reader.readAsDataURL(file); 
            }

            

        })
        
</script>
    
</body>
</html>
<?php
include ('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();



// seccion para registrar un nuevo usuario 
if(isset($_POST['submit'])){
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];
    $confirmarContraseña = $_POST['confirmarContraseña'];
    $ficha = 2901879;
    $estado = 2;
    $rol = $_POST['rolRegistro'];

    
    if($contraseña == $confirmarContraseña){
        
        $encriptacion = password_hash($contraseña, PASSWORD_DEFAULT, ["cost" => 12]);

        
        $validacion = $con->prepare("SELECT * FROM usuario WHERE id_documento = :documento OR correo = :email");
        $validacion->bindParam(':documento', $documento);
        $validacion->bindParam(':email', $email);
        $validacion->execute();

        $fila = $validacion->fetch(PDO::FETCH_ASSOC);

        if($fila){
            echo "<script>alert('El usuario ya existe o el correo está repetido');</script>";
        } else {
            
    
    $query = "INSERT INTO usuario (id_documento, nombre_completo, correo, contraseña, ficha, codigo_barras, id_estado, id_rol) 
            VALUES (:documento, :nombre, :email, :contrasena, :ficha, :codigo_barras, :estado, :rol)";

    $insert = $con->prepare($query);    


    $insert->bindParam(':documento', $documento);
    $insert->bindParam(':nombre', $nombre);
    $insert->bindParam(':email', $email);
    $insert->bindParam(':contrasena', $encriptacion);
    $insert->bindParam(':ficha', $ficha);
    $insert->bindParam(':codigo_barras', $documento);  
    $insert->bindParam(':estado', $estado);
    $insert->bindParam(':rol', $rol);

    $insert->execute();
        }
    }
}

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

        

$_SESSION['referer'] = $_SERVER['PHP_SELF'];
$codigo = $_SESSION['documento'];

$sql = $con->prepare("SELECT * FROM usuario INNER JOIN rol ON rol.id_rol = usuario.id_rol WHERE id_documento = :documento");
$sql->bindParam(':documento', $codigo);
$sql->execute();

$fila = $sql->fetch(PDO::FETCH_ASSOC);

// Obtener foto de perfil ::::::::::::::::::::::::::::.
$foto = $con->prepare("SELECT foto FROM usuario");
$foto->execute();
$resultado = $foto->fetchAll(PDO::FETCH_ASSOC);
?>


<?php
// Esta apretura de php se va a usar para la paginacion de regirstros 


$registrosPorPagina = 30;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if($paginaActual < 1){
    $paginaActual = 1;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;




if(isset($_POST['Eliminar'])){
    $fichaUsuario = $_POST['fichaUsuario'];
    $documentoUsuario = $_POST['documentoUsuario'];
    $contraseñaAdmin = $_POST['contraseñaAdmin'];
    
    
    $consulta = $con->prepare("SELECT * FROM usuario WHERE id_documento = '$codigo'");
    $consulta->execute();
    
    $fila = $consulta->fetch(PDO::FETCH_ASSOC);

    if($fila && password_verify($contraseñaAdmin, $fila['contraseña'])){
        $precuacion = $con->prepare("DELETE FROM usuario WHERE ficha = :ficha AND id_documento = :documentoUsuario");
        $precuacion->bindParam(":documentoUsuario",$documentoUsuario,PDO::PARAM_INT);
        $precuacion->bindParam(":ficha",$fichaUsuario,PDO::PARAM_INT);
        $precuacion->execute();
    
    }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuracion</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <link rel="stylesheet" href="css/buscarUsuario.css">
</head>
<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>

        <nav class="menu estado-menu" style="margin-top: 40px;">
            <div>
                <h1>Usuarios</h1>
                <p style="color:#858585">Administra los usuarios según la necesidad aquí.</p>
            </div>
            <div class="botonFuncionales">

            <div class="EliminarUsuarioDiv">
                <button class="botonEliminar" id="botonEliminar" type="button"><p id="textoEliminar">+</p>Eliminar Usuario</button>
            </div>

            <div class="AgregarUsuarioDiv">
                <button class="botonAgregar" id="botonAgregar" type="button"><p id="textoAbrir">+</p>Agregar Usuario</button>
            </div>

            </div>

        </nav>

        <main class="grid_contenido_plantilla">
            <hr style="border: 1px solid black;">

<div class="CrearUsuario" id="CrearUsuario">
    <form class="form" method="POST">
        <p class="title" id="tituloRegistro">Registro De Usuario</p>
        <p class="message" id="mensajeRegistro">Registrar Usuario En La Plataforma.</p>

        <div class="direccion" id="direccionRegistro">
            <div class="flex" id="flexRegistro">
                <span class="spanRegistro" id="labelNombre">Nombre Completo</span>
                <input id="nombre" name="nombre"  type="text" class="input inputsNuevoRegistro" placeholder="">

                <span class="spanRegistro" id="labelDocumento">Documento</span>
                <input id="documento" name="documento"  type="text" class="input inputsNuevoRegistro" placeholder="">

                <span class="spanRegistro" id="labelEmail">Email</span>
                <input id="email" name="email"  type="email" class="input inputsNuevoRegistro" placeholder="">
            </div>  

            <div id="grupoContraseñas">
                <span class="spanRegistro" id="labelContraseña">Contraseña</span>
                <input id="contraseña" name="contraseña"  type="password" class="input inputsNuevoRegistro" placeholder="">

                <span class="spanRegistro" id="labelConfirmarContraseña">Confirmar Contraseña</span>
                <input id="confirmarContraseña" name="confirmarContraseña"  type="password" class="input inputsNuevoRegistro" placeholder="">

                <span class="spanRegistro" >Rol</span>
                <select class="SeleccionarRol" name="rolRegistro" id="">
                    <option value="">Seleccione Un Rol</option>

                    <?php
                    // para mostrar los roles
                    $sqlrol = $con->prepare("SELECT * FROM rol");
                    $sqlrol->execute();
                    $sqlrol = $sqlrol->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach($sqlrol as $rol) {
                    ?>
                        <option value="<?php echo $rol['id_rol']; ?>"> <?php echo $rol['nombre_rol']; ?> </option>
                        
                    <?php
                    } ?>
                </select>
            </div>                
        </div>

        <input type="submit" value="Registrar" name="submit" id="registrarCrearUsuario" class="submit">
    </form>
</div>





            <div class="EliminarUsuario" id="EliminarUsuario">
                <form class="formEliminar" method="POST">
                    <p class="EliminarTitulo">Eliminar Usuario</p>
                    <p class="messageelimnar">Tenga En Cuenta Que Los Cambios No Son Reversibles.</p>

                        <span>Ficha Del Usuario</span>
                        <input name="fichaUsuario" required type="number" class="fichaUsuarioEliminar" placeholder="Ficha Del Usuario">
                        <span>Documento Del Usuario</span>
                        <input name="documentoUsuario" required type="number" class="documentoUsuario" placeholder="Documento Del Usuario">
                        <span>Contraseña Administrador</span>

                        <input name="contraseñaAdmin" required type="text" class="contraseñaAdmin" placeholder="Contraseña Administrador">


                    <input type="submit" value="Eliminar" name="Eliminar" class="EliminarUsuarioboton">
                </form>
            </div>

            <!-- seccion seleccionados  -->

            



                        



            <section class="verUsuarios" id="verUsuarios">

            <div class="buscador">
                <input type="text" id="buscarUsuario" placeholder="Buscar Usuario..." oninput="buscarUsuarios()">

                <section>
                    <div><button onclick="enviarDatos()" id="boton">Click</button></div>
                </section>
            </div>

            

            <div class="tabla-container">
                <table class="tabla" id="tablaUsuarios">
                    <tr>
                        <th class="filaUsuario">Usuario</th>
                        <th class="filaRol">Rol</th>
                        <th class="filaFicha">Ficha</th>
                        <th class="filaPrograma">Programa De Formacion</th>  
                        <th class="filaFormacion">Nivel De Formacion</th>
                        <th class="filaJornada">Jornada</th>
                        <th class="filaEstado">Estado</th>
                        <th class="filaEstado"></th>
                    </tr>

                    <?php 
                    // aqui me esttroy trayendo varias tablas para poder usarlas a lo largo de la interfaz

                    // aqui estoy contando la cantidad de registros para la paginacion
                    $totalRegistrosQuery = $con->query("SELECT COUNT(*) as total FROM usuario");
                    $totalRegistros = $totalRegistrosQuery->fetch(PDO::FETCH_ASSOC)['total'];

                    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);
                    
                $sql = $con->prepare("SELECT usuario.*, usuario.foto, fichas.n_ficha, programa.nombrePrograma,rol.nombre_rol,estado.estado, nivelformacion.nivelDeFormacion, jornada.jornada  FROM usuario
                                        INNER JOIN rol ON rol.id_rol = usuario.id_rol
                                        LEFT JOIN fichas ON fichas.n_ficha = usuario.ficha
                                        INNER JOIN estado ON estado.id_estado = usuario.id_estado
                                        LEFT JOIN programa ON fichas.id_programaFormacion = programa.id_programaFormacion
                                        LEFT JOIN nivelformacion ON fichas.id_nivelFormacion = nivelformacion.id_nivelFormacion
                                        LEFT JOIN jornada ON fichas.id_jornada = jornada.id_jornada
                                        -- WHERE usuario.id_rol != 1
                                        LIMIT :limit OFFSET :offset ");
                    $sql->bindParam(":limit",$registrosPorPagina, PDO::PARAM_INT);
                    $sql->bindParam(":offset",$offset,PDO::PARAM_INT);
                    $sql->execute();

                    

// este codigo de aqui es para las fotos de los ususairos gracias a esto pueod dar direcciones a las iamgenes e indntificarlas
                    $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($fila as $resu){
                        $fotoActual = $resu['foto'];
                        $mostrar = "../../uploads/" . $fotoActual;
                        $usu = "../../uploadsAdmin/usu.png";


                    ?>

                    <tr>
                        <th class="datos datosNombre">
                            
                                <input type="checkbox" class="checkBoX" name="seleccionados[]" id="seleccionados" value="<?php echo $resu['id_documento'] ?>">
                            
                            
                            <img style="border-radius: 15px;" width="30px" height="30px" src="<?php echo !empty($resultado['foto']) ? $mostrar : $usu ?>" alt="">
                            <div style="flex-direction: column;">
                                <input class="editarNombre inputEdicion" disabled type="text" placeholder="<?php echo $resu['nombre_completo'] ?>">   
                                <input class="editarCorreo inputEdicion" disabled type="email" placeholder="<?php echo $resu['correo'] ?>">
                                <input class="editarCorreo inputEdicion" disabled type="email" placeholder="<?php echo $resu['id_documento'] ?>">
                            </div>
                        </th>
                        <th class="datos"><?php echo $resu['nombre_rol'] ?></th>
                        <th class="datos"><?php echo !empty($resu['ficha']) ? $resu['ficha'] : "Sin Ficha"  ?></th>
                        <th class="datos"><?php echo !empty($resu['nombrePrograma']) ? $resu['nombrePrograma'] : "Sin Programa" ?></th>
                        <th class="datos"><?php echo !empty($resu['nivelDeFormacion']) ? $resu['nivelDeFormacion'] : "Sin Formacion" ?></th>
                        <th class="datos"><?php echo !empty($resu['jornada']) ? $resu['jornada'] : "Sin Jornada" ?></th>
                        <th class="datos"><?php echo !empty($resu['estado']) ? $resu['estado'] : "vacio" ?></th>
                        <th class="datos">
                            <input type="button" id="botonTresPuntos" class="botonTresPuntos">
                                <img class="imagenDeLostrespuntos" src="./imgAdmin/tres puntos.png" width="30px" onclick="abrirVentana('actualizar.php?documento=<?php echo $resu['id_documento']; ?>')" height="30px"alt="" >



                            </div>
                        </th>
                    </tr>

                    <?php } ?>
                </table>

                <?php 
                echo '<div class="paginacion">';
                if ($paginaActual > 1) {
                    echo '<a class="paginacionB" href="?pagina=' . ($paginaActual - 1) . '">Anterior</a>';
                }
                
                for ($i = 1; $i <= $totalPaginas; $i++) {
                    echo '<a class="paginacionB" href="?pagina=' . $i . '" ' . ($i == $paginaActual ? 'class="activo"' : '') . '>' . $i . '</a>';
                }
                
                if ($paginaActual < $totalPaginas) {
                    echo '<a class="paginacionB" href="?pagina=' . ($paginaActual + 1) . '">Siguiente</a>';
                }
                echo '</div>';
                ?>
             </div>
            </section>
        </main> 
    </div>
    <script src="pruebas/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="../../js/js.js"></script>
    <script src="./js/crearUsuario.js"></script>
    <script src="js/buscarUsuario.js"></script>
    <script src="./js/seleccionarVariosUsuario.js"></script>

    <script>
        function abrirVentana(url) {
    const ancho = window.screen.width / 1.5
    const alto =   window.screen.height / 1.5

    const x = (window.screen.width / 2) - (ancho / 2);
    const y = (window.screen.height / 2) - (alto / 2);
    
    const opciones = `width=${ancho},height=${alto},top=${y},left=${x},toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no`;
    window.open(url, '', opciones);

}
    </script>
</body>
</html>

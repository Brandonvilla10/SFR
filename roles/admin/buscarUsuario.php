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

    $ficha = 1;
    $estado = 2;
    $rol = 2;

    
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


$registrosPorPagina = 5;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if($paginaActual < 1){
    $paginaActual = 1;
}

$offset = ($paginaActual - 1) * $registrosPorPagina
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
            <div class="AgregarUsuarioDiv">
                <button class="botonAgregar" id="botonAgregar" type="button"><p id="textoAbrir">+</p>Agregar Usuario</button>
            </div>
        </nav>

        <main class="grid_contenido_plantilla">
            <hr style="border: 1px solid black;">
            <div class="CrearUsuario" id="CrearUsuario">
                <form class="form" method="POST">
                    <p class="title">Registro De Usuario</p>
                    <p class="message">Registrar Usuario En La Plataforma.</p>

                    <div class="flex">
                        <label>
                            <input name="nombre" required type="text" class="input" placeholder="">
                            <span>Nombre Completo</span>
                        </label>
                        <label>
                            <input name="documento" required type="text" class="input" placeholder="">
                            <span>Documento</span>
                        </label>
                    </div>

                    <label>
                        <input name="email" required type="email" class="input" placeholder="">
                        <span>Email</span>
                    </label>

                    <label>
                        <input name="contraseña" required type="password" class="input" placeholder="">
                        <span>Contraseña</span>
                    </label>

                    <label>
                        <input name="confirmarContraseña" required type="password" class="input" placeholder="">
                        <span>Confirmar Contraseña</span>
                    </label>

                    <input type="submit" value="Registrar" name="submit" class="submit">
                </form>
            </div>

            <section class="verUsuarios" id="verUsuarios">
            <div class="tabla-container">
                <table class="tabla">
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
                    $sql = $con->prepare("SELECT * FROM usuario INNER JOIN rol ON rol.id_rol = usuario.id_rol
                                          INNER JOIN fichas ON fichas.n_ficha = usuario.ficha
                                          INNER JOIN estado ON estado.id_estado = usuario.id_estado
                                          LIMIT :limit OFFSET :offset");
                    $sql->bindParam(":limit",$registrosPorPagina, PDO::PARAM_INT);
                    $sql->bindParam(":offset",$offset,PDO::PARAM_INT);
                    $sql->execute();

                    // aqui estoy contando la cantidad de registros para la paginacion
                    $totalRegistrosQuery = $con->query("SELECT COUNT(*) as total FROM usuario");
                    $totalRegistros = $totalRegistrosQuery->fetch(PDO::FETCH_ASSOC)['total'];

                    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// este codigo de aqui es para las fotos de los ususairos gracias a esto pueod dar direcciones a las iamgenes e indntificarlas
                    $fila = $sql->fetchAll(PDO::FETCH_ASSOC);

                    foreach($fila as $resu){
                        $fotoActual = $resu['foto'];
                        $mostrar = "../../uploads/" . $fotoActual;
                        $usu = "../../uploads/usu.png";
                    ?>

                    <tr>
                        <th class="datos datosNombre">
                            <img style="border-radius: 15px;" width="30px" height="30px" src="<?php echo !empty($resultado['foto']) ? $mostrar : $usu ?>" alt="">
                            <div style="flex-direction: column;">
                                <input class="editarNombre inputEdicion" type="text" placeholder="<?php echo $resu['nombre_completo'] ?>">   
                                <input class="editarCorreo inputEdicion" type="email" placeholder="<?php echo $resu['correo'] ?>">
                                <input class="editarCorreo inputEdicion" type="email" placeholder="<?php echo $resu['id_documento'] ?>">
                            </div>
                        </th>
                        <th class="datos"><?php echo $resu['nombre_rol'] ?></th>
                        <th class="datos"><?php echo $resu['ficha'] ?></th>
                        <th class="datos"><?php echo $resu['nombre_formacion'] ?></th>
                        <th class="datos"><?php echo $resu['nivelDeFormacion'] ?></th>
                        <th class="datos"><?php echo $resu['jornada'] ?></th>
                        <th class="datos"><?php echo $resu['estado'] ?></th>
                        <th class="datos">
                            <input type="button" id="botonTresPuntos" class="botonTresPuntos">
                                <img src="./imgAdmin/tres puntos.png" width="30px" onclick="abrirVentana('actualizar.php?documento=<?php echo $resu['id_documento']; ?>')" height="30px"alt="" >



                            </div>
                        </th>
                    </tr>

                    <?php } ?>
                </table>

                <?php 
                echo '<div class="paginacion">';
                if ($paginaActual > 1) {
                    echo '<a href="?pagina=' . ($paginaActual - 1) . '">Anterior</a>';
                }
                
                for ($i = 1; $i <= $totalPaginas; $i++) {
                    echo '<a href="?pagina=' . $i . '" ' . ($i == $paginaActual ? 'class="activo"' : '') . '>' . $i . '</a>';
                }
                
                if ($paginaActual < $totalPaginas) {
                    echo '<a href="?pagina=' . ($paginaActual + 1) . '">Siguiente</a>';
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


    <script>
        function abrirVentana(url) {
    const ancho = window.screen.width / 2
    const alto =   window.screen.height / 2

    const x = (window.screen.width / 2) - (ancho / 2);
    const y = (window.screen.height / 2) - (alto / 2);
    
    const opciones = `width=${ancho},height=${alto},top=${y},left=${x},toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no`;
    window.open(url, '', opciones);
}

    </script>
</body>
</html>

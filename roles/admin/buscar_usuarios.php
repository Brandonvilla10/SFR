<?php
include ('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();



$registrosPorPagina = 30;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if($paginaActual < 1){
    $paginaActual = 1;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';


$sql = "SELECT usuario.*, usuario.foto, fichas.n_ficha, programa.nombrePrograma, rol.nombre_rol, estado.estado, nivelformacion.nivelDeFormacion, jornada.jornada  
        FROM usuario
        INNER JOIN rol ON rol.id_rol = usuario.id_rol
        INNER JOIN fichas ON fichas.n_ficha = usuario.ficha
        INNER JOIN estado ON estado.id_estado = usuario.id_estado
        INNER JOIN programa ON fichas.id_programaFormacion = programa.id_programaFormacion
        INNER JOIN nivelformacion ON fichas.id_nivelFormacion = nivelformacion.id_nivelFormacion
        INNER JOIN jornada ON fichas.id_jornada = jornada.id_jornada
        WHERE usuario.id_documento LIKE :buscar";

$stmt = $con->prepare($sql);
$buscarTerm = '%' . $buscar . '%';
$stmt->bindParam(':buscar', $buscarTerm, PDO::PARAM_STR);
$stmt->execute();

$fila = $stmt->fetchAll(PDO::FETCH_ASSOC);





echo "

                
    <tr>
                        <th class='filaUsuario'>Usuario</th>
                        <th class='filaRol'>Rol</th>
                        <th class='filaFicha'>Ficha</th>
                        <th class='filaPrograma'>Programa De Formacion</th>  
                        <th class='filaFormacion'>Nivel De Formacion</th>
                        <th class='filaJornada'>Jornada</th>
                        <th class='filaEstado'>Estado</th>
                        <th class='filaEstado'></th>
                    </tr>
";

foreach ($fila as $resu) {
    $fotoActual = $resu['foto'];
    $mostrar = "../../uploads/" . $fotoActual;
    $usu = "../../uploads/usu.png";
    echo "

    <tr>
    <th class='datos datosNombre'>
    <input type='checkbox' class='checkBoX' name='seleccionados[]' id='seleccionados' value='" .  $resu['id_documento']  . "' >

            <img style='border-radius: 15px;' width='30px' height='30px' src='" . (!empty($resu['foto']) ? $mostrar : $usu) . "' alt=''>
            <div style='flex-direction: column;'>
                <input class='editarNombre inputEdicion' type='text' placeholder='" . $resu['nombre_completo'] . "'>   
                <input class='editarCorreo inputEdicion' type='email' placeholder='" . $resu['correo'] . "'>
                <input class='editarCorreo inputEdicion' type='email' placeholder='" . $resu['id_documento'] . "'>
            </div>
        </th>
        <th class='datos'>" . $resu['nombre_rol'] . "</th>
        <th class='datos'>" . $resu['ficha'] . "</th>
        <th class='datos'>" . $resu['nombrePrograma'] . "</th>
        <th class='datos'>" . $resu['nivelDeFormacion'] . "</th>
        <th class='datos'>" . $resu['jornada'] . "</th>
        <th class='datos'>" . $resu['estado'] . "</th>
        <th class='datos'>
            <input type='button' id='botonTresPuntos' class='botonTresPuntos'>
            <img src='./imgAdmin/tres puntos.png' width='30px' onclick=\"abrirVentana('actualizar.php?documento=" . $resu['id_documento'] . "')\" height='30px' alt=''>
        </th>
    </tr>";
}
?>

<script src="js/buscarUsuario.js"></script>
<script src="./js/seleccionarVariosUsuario.js"></script>

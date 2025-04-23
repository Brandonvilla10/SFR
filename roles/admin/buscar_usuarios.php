<?php
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();



$registrosPorPagina = 30;

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

if ($paginaActual < 1) {
    $paginaActual = 1;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;

$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';


$sql = "SELECT 
                            usuario.nombre_completo, 
                            usuario.foto, 
                            usuario.id_documento, 
                            usuario.correo, 
                            fichas.n_ficha, 
                            programa.nombrePrograma, 
                            rol.nombre_rol, 
                            estado.estado, 
                            nivelformacion.nivelDeformacion, 
                            jornada.jornada 
                            FROM usuario
                            LEFT JOIN detallematricula ON usuario.id_documento = detallematricula.id_documento 
                            LEFT JOIN fichas ON fichas.n_ficha = detallematricula.id_ficha
                            LEFT JOIN estado ON estado.id_estado = detallematricula.id_estado
                            LEFT JOIN rol ON rol.id_rol = usuario.id_rol
                            LEFT JOIN programa ON programa.id_programaFormacion = fichas.id_programaFormacion
                            LEFT JOIN nivelformacion ON nivelformacion.id_nivelFormacion = fichas.id_nivelFormacion
                            LEFT JOIN jornada ON jornada.id_jornada = fichas.id_jornada
    
         WHERE usuario.id_documento LIKE :buscar OR usuario.nombre_completo LIKE :buscarNombre";

$buscarTerm = '%' . $buscar . '%';
$stmt = $con->prepare($sql);
$stmt->bindParam(':buscar', $buscarTerm, PDO::PARAM_STR);
$stmt->bindParam(':buscarNombre', $buscarTerm, PDO::PARAM_STR);
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
            <input type='checkbox' class='checkBoX' name='seleccionados[]' value='{$resu['id_documento']}'>

            <img style='border-radius: 15px;' width='30px' height='30px' src='" . (!empty($resu['foto']) ? $mostrar : $usu) . "' alt=''>
            <div style='flex-direction: column;'>
                <input class='editarNombre inputEdicion' disabled type='text' placeholder='{$resu['nombre_completo']}'>
                <input class='editarCorreo inputEdicion' disabled type='email' placeholder='{$resu['correo']}'>
                <input class='editarCorreo inputEdicion' disabled type='email' placeholder='{$resu['id_documento']}'>
            </div>
        </th>
        <th class='datos'>{$resu['nombre_rol']}</th>
        <th class='datos'>" . (!empty($resu['n_ficha']) ? $resu['n_ficha'] : "Sin Ficha") . "</th>
        <th class='datos'>" . (!empty($resu['nombrePrograma']) ? $resu['nombrePrograma'] : "Sin Programa") . "</th>
        <th class='datos'>" . (!empty($resu['nivelDeFormacion']) ? $resu['nivelDeFormacion'] : "Sin Formacion") . "</th>
        <th class='datos'>" . (!empty($resu['jornada']) ? $resu['jornada'] : "Sin Jornada") . "</th>
        <th class='datos'>" . (!empty($resu['estado']) ? $resu['estado'] : "Vacio") . "</th>
        <th class='datos'>
            <input type='button' id='botonTresPuntos' class='botonTresPuntos'>
            <img class='imagenDeLostrespuntos' src='./imgAdmin/tres puntos.png' width='30px' height='30px' onclick='abrirVentana(\"actualizar.php?documento={$resu['id_documento']}\")' alt=''>
        </th>
    </tr>
    ";
}

?>

<script src="js/buscarUsuario.js"></script>
<script src="./js/seleccionarVariosUsuario.js"></script>
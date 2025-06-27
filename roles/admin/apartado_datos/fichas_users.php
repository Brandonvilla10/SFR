<?php
session_start();
require_once('../../../database/database.php');
include('../components/alert.php');
$conexion = new database;
$con = $conexion->conectar();
include('../../../includes/sesion_validar_datos.php');

$codigo = isset($_SESSION['documento']) ? $_SESSION['documento'] : '';

// Aca defino la página actual
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limite_registros = 5;

// Aca esta el limite de los objetos
$start_record = ($page - 1) * $limite_registros;

// Contar total de registros
$consulta_total = $con->prepare("SELECT COUNT(*) FROM detallematricula  WHERE id_estado = 1 and id_ficha = :ficha");
$consulta_total->bindParam(':ficha', $_GET['ficha'], PDO::PARAM_INT);
$consulta_total->execute();
$total_filas = $consulta_total->fetchColumn();
$paginas = ceil($total_filas / $limite_registros);

// Obtener datos paginados
$consulta = $con->prepare("SELECT u.id_documento,d.id_ficha,u.nombre_completo,e.estado,u.foto FROM detallematricula d 
INNER JOIN usuario u ON d.id_documento = u.id_documento
INNER JOIN estado e ON e.id_estado = d.id_estado
WHERE d.id_estado = 1 and id_ficha = :ficha   LIMIT :start, :limit");
$consulta->bindParam(':ficha', $_GET['ficha'], PDO::PARAM_INT);
$consulta->bindParam(':start', $start_record, PDO::PARAM_INT);
$consulta->bindParam(':limit', $limite_registros, PDO::PARAM_INT);
$consulta->execute();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../assets/img/LogoFR.png">
    <title>Fichas</title>
    <link rel="stylesheet" href="css/marcas.css">
    <link rel="stylesheet" href="asideBar/asidebar.css">
    <link rel="stylesheet" href="../components/modalAgregar.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../components/modaEliminar.css">

</head>

<body>
    <div class="container_grid">
        <aside class="aside_barra">
            <?php include('asideBar/asideBar.php') ?>
        </aside>

        <main class="grid_contenido_plantilla">
            <div class="titulo">
                <div class="tituloSection">
                    <h1>FICHA <?php echo $_GET['ficha'] ?></h1>
                    <p>Verifica los aprendices inscritos</p>
                </div>

            </div>

            <hr class="separador">
            <div class="contenido_tabla" id="contenido">
                <input class="BOTON_FILTRAR" id="filtro" placeholder="Filtrar por cedula" oninput="filtrarPorDocumento(this.value)">

                <?php if ($consulta->rowCount() == 0) { ?>
                    <div class="notificacion">
                        <h4>No hay aprendices inscritos a esta ficha</h4>
                    </div>
                <?php } else { ?>
                    <div class="encabezado">
                        <h4>Foto</h4>
                        <h4>Cedula</h4>
                        <h4>Nombre</h4>
                        <h4>Estado</h4>
                        <h4>Acciones</h4>

                    </div>
                    <?php foreach ($consulta as $filas) { ?>
                        <div class="vehiculos">
                            <h4><img src="../../../uploads/usu.png" alt="" width="30px" height="30px"></h4>

                            <h4><?php echo $filas['id_documento']; ?></h4>
                            <h4><?php echo $filas['nombre_completo']; ?></h4>
                            <h4><?php echo $filas['estado']; ?></h4>

                            <h4>
                                <div class="dropdown">
                                    <button onclick="desplegar('<?php echo $filas['id_documento']; ?>')" class="dropbtn">
                                        <ion-icon name="chevron-down-outline" size="large"></ion-icon>
                                    </button>
                                    <div id="myDropdown_<?php echo $filas['id_documento']; ?>" class="dropdown-content">
                                        <a href="../actualizar.php?documento=<?php echo $filas['id_documento'] ?>&route=apartado_datos/fichas.php">Editar Usuario</a>
                                        <a href="GenerarQR.php?documento=<?php echo $filas['id_documento']; ?>">Generar QR</a>
                                        <form action="../set_session.php" method="post">
                                            <button class="botonDropDown" type="submit" name="documentoUsuario_vehiculo" value="<?php echo $filas['id_documento']; ?>">Crear Vehiculos</button>
                                        </form>
                                        <form action="../set_session.php" method="post">
                                            <button class="botonDropDown" type="submit" name="documento_usuario" value="<?php echo $filas['id_documento']; ?>">Crear Objetos</button>
                                        </form>
                                    </div>
                                </div>
                            </h4>

                        </div>
                    <?php } ?>
                <?php }  ?>

                <div class="paginacion" id="paginacion">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&ficha=<?php echo $_GET['ficha']; ?>" class="pag-btn">&laquo; Anterior</a>

                    <?php endif; ?>

                    <?php
                    $start_page = max(1, $page - 1);
                    $end_page = min($paginas, $page + 1);

                    if ($start_page == 1 && $paginas >= 3) {
                        $end_page = 3;
                    }

                    if ($end_page == $paginas && $paginas >= 3) {
                        $start_page = max(1, $paginas - 2);
                    }

                    for ($i = $start_page; $i <= $end_page; $i++):
                    ?>
                        <a href="?page=<?php echo $i; ?>&ficha=<?php echo $_GET['ficha']; ?>" class="pag-btn <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>

                    <?php endfor; ?>

                    <?php if ($page < $paginas): ?>
                        <a href="?page=<?php echo $page + 1; ?>&ficha=<?php echo $_GET['ficha']; ?>" class="pag-btn">Siguiente &raquo;</a>

                    <?php endif; ?>
                </div>

                <button class="footer-button animate-button" onclick="window.location.href='fichas.php'">Regresar</button>

            </div>

            <!------------------------------------------------ MODAL DE CREAR FICHA ----------------------------------->


            <div class="modalAgregar" id="modalAgregar">
                <div class="contenedormodalAgregar">
                    <div class="headermodalAgregar">
                        <h3>Crear Ficha</h3>
                        <button class="cerrarmodalAgregar" id="cerrarmodalAgregar">&times;</button>
                    </div>
                    <div class="cuerpomodalAgregar">
                        <form action="fichas.php" method="post">
                            <div class="campo-formulario">
                                <label for="num_ficha">Numero de ficha</label>
                                <input class="input-modal" type="number" id="num_ficha" name="num_ficha" placeholder="Digite el numero de ficha" required>
                            </div>
                            <div class="campo-formulario">
                                <label for="programa">Programa de Formacion</label>
                                <select id="programa" name="programa" required>
                                    <option value="">Seleccione un programa</option>
                                    <?php foreach ($programas as $programa): ?>
                                        <option value="<?php echo $programa['id_programaFormacion'] ?>"><?php echo $programa['nombrePrograma']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="conjuntoModal">

                                <div>
                                    <label for="nivel">Nivel de Formacion</label>
                                    <select id="nivel" name="nivel" required>
                                        <option value="">Seleccione un nivel</option>
                                        <?php foreach ($niveles as $nivel): ?>
                                            <option value="<?php echo $nivel['id_nivelFormacion'] ?>"><?php echo $nivel['nivelDeformacion']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="jornada">Jornada de Formacion</label>
                                    <select id="jornada" name="jornada" required>
                                        <option value="">Seleccione una jornada</option>
                                        <?php foreach ($jornadas as $jornada): ?>
                                            <option value="<?php echo $jornada['id_jornada']; ?>"><?php echo $jornada['jornada']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="conjuntoModal">
                                <div>
                                    <label for="fecha_ini">Fecha Inicio</label>
                                    <input class="input-modal" type="date" id="fecha_ini" name="fecha_ini" required>
                                </div>
                                <div>
                                    <label for="fecha_fin">Fecha Fin</label>
                                    <input class="input-modal" type="date" id="fecha_fin" name="fecha_fin" required>
                                </div>
                            </div>

                            <div class="campo-formulario">
                                <label>Dias de Formacion</label>
                                <div class="checkbox-group">
                                    <input type="checkbox" name="dias[]" value="Lunes">
                                    <h5>Lunes</h5>
                                    <input type="checkbox" name="dias[]" value="Martes">
                                    <h5>Martes</h5>
                                    <input type="checkbox" name="dias[]" value="Miercoles">
                                    <h5>Miercoles</h5>
                                    <input type="checkbox" name="dias[]" value="Jueves">
                                    <h5>Jueves</h5>
                                    <input type="checkbox" name="dias[]" value="Viernes">
                                    <h5>Viernes</h5>
                                    <input type="checkbox" name="dias[]" value="Sabado">
                                    <h5>Sabado</h5>
                                </div>
                            </div>
                            <div class="botonesmodalAgregar">
                                <button type="button" class="cancelar-btn" id="cancelarBuscar">Cancelar</button>
                                <button type="submit" name="submitfichas" class="buscar-btn">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        const modalAgregar = document.getElementById('modalAgregar');
        const abrirBuscar = document.getElementById('abrirAgregar');
        const cerrarmodalAgregar = document.getElementById('cerrarmodalAgregar');
        const cancelarBuscar = document.getElementById('cancelarBuscar');

        function cerrarBuscar() {
            modalAgregar.style.visibility = 'hidden';
            modalAgregar.style.opacity = '0';
        }

        function abrirBuscarFunction() {
            modalAgregar.style.visibility = 'visible';
            modalAgregar.style.opacity = '1';
        }

        function cerrarBuscar() {
            modalAgregar.style.visibility = 'hidden';
            modalAgregar.style.opacity = '0';
        }

        abrirBuscar.addEventListener('click', abrirBuscarFunction);
        cerrarmodalAgregar.addEventListener('click', cerrarBuscar);
        cancelarBuscar.addEventListener('click', cerrarBuscar);
    </script>

    <?php include("../components/modaEliminar.php"); ?>

    <script>
        const abrirEliminar = document.getElementById('abrirEliminar');
        document.addEventListener('DOMContentLoaded', function() {
            abrirEliminar.addEventListener('click', function() {
                abrir("Identificador De La Ficha", 2, "Digite El Numero De Ficha", "Eliminar Ficha");
            });
        });


        //  Validacion Formulario

        const fechaInicio = document.getElementById('fecha_ini');
        const fechaFin = document.getElementById('fecha_fin');

        fechaFin.addEventListener('blur', function() {
            if (fechaInicio.value > fechaFin.value) {
                mostrarModal('mistake', 'Fecha Fin Mayor a la fecha inicio');
                fechaFin.value = '';
            }
        })

        function desplegar(userId) {

            let dropdowns = document.getElementsByClassName("dropdown-content");
            for (let i = 0; i < dropdowns.length; i++) {
                let openDropdown = dropdowns[i];
                if (openDropdown.id !== "myDropdown_" + userId && openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }

            document.getElementById("myDropdown_" + userId).classList.toggle("show");
        }


        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn, .dropbtn *')) {
                let dropdowns = document.getElementsByClassName("dropdown-content");
                let i;
                for (i = 0; i < dropdowns.length; i++) {
                    let openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }



        // FUNCIONALIDAD DE FILTRAR POR CEDULA

        const contenido = document.getElementById('contenido');
        const ficha = new URLSearchParams(window.location.search).get('ficha');

        function filtrarPorDocumento(serial) {
            const objetos = document.querySelectorAll('.vehiculos');
            const usuario_buscado = serial.toUpperCase();

            const filtro = document.getElementById('filtro')

            filtro.addEventListener("input", () => {
                if (filtro.value.length > 10) {
                    filtro.value = filtro.value.substring(0, 10)
                }
            })

            let consulta = `SELECT u.id_documento,d.id_ficha,u.nombre_completo,e.estado,u.foto FROM detallematricula d 
                            INNER JOIN usuario u ON d.id_documento = u.id_documento
                            INNER JOIN estado e ON e.id_estado = d.id_estado
                            WHERE d.id_estado = 1 AND d.id_ficha = ${ficha} AND u.id_documento LIKE '%${usuario_buscado}%' LIMIT 5`;

            fetch('../fetch/busquedaBarra.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        consulta: consulta
                    })
                })
                .then((response) => response.json())
                .then(data => {


                    if (data.error) {
                        const vehiculos = document.querySelectorAll('.vehiculos');
                        vehiculos.forEach(vehiculo => {
                            vehiculo.remove();
                        });

                    } else {
                        const vehiculos = document.querySelectorAll('.vehiculos');
                        vehiculos.forEach(vehiculo => {
                            vehiculo.remove();
                        });
                        data.forEach(generales => {
                            const cartas = document.createElement('div');
                            cartas.classList.add('vehiculos');
                            cartas.innerHTML = '';
                            cartas.innerHTML = ` 
                        <h4><img src='../../../uploads/usu.png'" width ='30px' height='30px'></h4>
                        <h4>${generales.id_documento}</h4>
                        <h4>${generales.nombre_completo}</h4>
                        <h4>${generales.estado}</h4>
                        <h4>
                                <div class="dropdown">
                                    <button onclick="desplegar('${generales.id_documento}')" class="dropbtn">
                                        <ion-icon name="chevron-down-outline" size="large"></ion-icon>
                                    </button>
                                    <div id="myDropdown_${generales.id_documento}" class="dropdown-content">
                                        <a href="../actualizar.php?documento=${generales.id_documento}&route=apartado_datos/fichas.php">Editar Usuario</a>
                                        <a href="GenerarQR.php?documento=${generales.id_documento}">Generar QR</a>
                                        <form action="../set_session.php" method="post">
                                            <button class="botonDropDown" type="submit" name="documentoUsuario_vehiculo" value="${generales.id_documento}">Crear Vehiculos</button>
                                        </form>
                                        <form action="../set_session.php" method="post">
                                            <button class="botonDropDown" type="submit" name="documento_usuario" value="${generales.id_documento}">Crear Objetos</button>
                                        </form>
                                    </div>
                                </div>
                        </h4>
                       
                        `;


                            const paginacion = document.getElementById('paginacion');
                            contenido.insertBefore(cartas, paginacion);
                        })

                    }

                }).catch(error => {
                    console.error('Error:', error);
                })
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

</body>

</html>
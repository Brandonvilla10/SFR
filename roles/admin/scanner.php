<?php
include('../../database/database.php');
$conexion = new database;
$con = $conexion->conectar();
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lector de QR</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <link rel="stylesheet" href="asidebar/asidebar.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="/css/output.css">
    <link rel="stylesheet" href="./css/scanner.css">

</head>

<body>

    <div class="container_grid">

        <div class="alerta" id="alerta">
            <p class="alertaBienvenida" id="alertaBienvenida"></p>
        </div>

        <aside class="aside_barra">
            <?php include('asidebar/asideBar.php') ?>
        </aside>



        <div class="instructions">

            <div class="container">
                <h1>Lector de Código QR</h1>



                <button id="startButton">Iniciar Cámara</button>
                <button id="stopButton" disabled>Detener Cámara</button>
            </div>
        </div>


        <main class="grid_contenido_plantilla">

            <div class="container">

                <div id="videoContainer">
                    <video id="video" playsinline></video>
                    <canvas id="canvas"></canvas>
                </div>

                <div id="result">El resultado del código QR aparecerá aquí...</div>
            </div>




        </main>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const resultDiv = document.getElementById('result');
        const startButton = document.getElementById('startButton');
        const stopButton = document.getElementById('stopButton');
        const videoContainer = document.getElementById("videoContainer");
        const alerta = document.getElementById("alerta");
        const alertaBienvenida = document.getElementById("alertaBienvenida");

        let stream = null;
        let scanning = false;

        // Contexto para el canvas
        const canvasContext = canvas.getContext('2d');

        // Función para iniciar la cámara
        async function startCamera() {
            try {
                // Detener cualquier transmisión existente
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                // Obtener acceso a la cámara
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment', // Preferir cámara trasera en móviles
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    },
                    audio: false
                });

                video.srcObject = stream;
                video.play();

                startButton.disabled = true;
                stopButton.disabled = false;

                // Iniciar el escaneo
                scanning = true;
                scanQR();
            } catch (err) {
                console.error("Error al acceder a la cámara:", err);
                resultDiv.textContent = "Error al acceder a la cámara: " + err.message;
            }
        }

        // Función para detener la cámara
        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            video.srcObject = null;
            scanning = false;

            startButton.disabled = false;
            stopButton.disabled = true;

            resultDiv.textContent = "Cámara detenida. Haz clic en 'Iniciar Cámara' para escanear otro código.";
        }

        // Función para escanear el código QR
        function scanQR() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                // Ajustar el tamaño del canvas al del video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Dibujar el frame actual del video en el canvas
                canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Obtener los datos de la imagen del canvas
                const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);

                // Escanear el código QR
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                // Si se encontró un código QR
                if (code) {

                    const regex = /^\d{8,}$/;



                    resultDiv.innerHTML = `<strong>Contenido del QR:</strong><br>${code.data}`;
                    // Opcional: puedes detener el escaneo automáticamente al encontrar un código

                    if (regex.test(code.data)) {

                        stopCamera();

                        videoContainer.style.background = "green"
                        setTimeout(() => {
                            startCamera();
                        }, 7);


                        async function registrarUsuario() {
                            const response = await fetch('./fetch/registrarEntrada.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: code.data
                            })

                            const respuesta = await response.json();

                            alertaBienvenida.innerHTML = respuesta.mensaje + " " + respuesta.id_documento

                            alerta.classList.add("aparecer");
                            alerta.style.background = respuesta.color
                            setTimeout(() => {
                                alerta.classList.remove("aparecer");
                            }, 3000);



                        }

                        registrarUsuario()
                    } else {
                        stopCamera();
                        videoContainer.style.background = "red"
                        setTimeout(() => {
                            alert("Alerta codigo qr no valido! Valor del qr ingresado -> :  " + code.data)
                        }, 500);
                    }
                }
            }

            // Continuar escaneando
            // Esperar 2 segundos antes de volver a escanear
            if (scanning) {
                setTimeout(() => {
                    videoContainer.style.opacity = 1
                    requestAnimationFrame(scanQR);

                }, 2000); // 2000 ms = 2 segundos


            }



        }

        // Event listeners para los botones
        startButton.addEventListener('click', startCamera);
        stopButton.addEventListener('click', stopCamera);

        // Detener la cámara al salir de la página
        window.addEventListener('beforeunload', stopCamera);
    </script>
</body>

</html>
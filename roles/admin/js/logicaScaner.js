// aqui importo la funcion para meter los botones del modal de observacion

import {
  agregarBotonesObservacion,
  obtenerObservacionesFuncion,
  agregarBotonesVehiculos,
  limpiarObservaciones,
} from "./modalObservacion.js";

let alerta = document.getElementById("alerta");
let alertaBienvenida = document.getElementById("alertaBienvenida");

let entradaObjetos = [];

function toggleSelection(id_serial) {
  // esta funcion solo sirve para los objetos con esto los meto en un array para luego enviarlos y bueno registrar la entrada de ellos
  const serial = id_serial.dataset.id;

  id_serial.classList.toggle("seleccionado");

  if (!id_serial.classList.contains("seleccionado")) {
    const indice = entradaObjetos.indexOf(serial);
    if (indice !== -1) {
      entradaObjetos.splice(indice, 1);
    }
  } else {
    entradaObjetos.push(serial);
  }

  // ESTA belleza de aqui es para las obsevaciones que vienen del otro archivo js
  const observacionesActuales = obtenerObservacionesFuncion();

 
}

// esta funcion es para los vehiculos

let entradavehiculos = [];

function toggleSelectionVehiculo(id_placa) {
  const placa = id_placa.dataset.id;

  id_placa.classList.toggle("seleccionado");

  if (!id_placa.classList.contains("seleccionado")) {
    const indice = entradavehiculos.indexOf(placa);
    if (indice != -1) {
      entradavehiculos.splice(indice, 1);
    }
  } else {
    entradavehiculos.push(placa);
  }

  const observacionesActuales = obtenerObservacionesFuncion();

  
}

let documento;

// esta funcion asincrona se encarga de todo el proceso de registroDeEntrada que esoty haciendo? basicamenete 3 fetchs en una misma funcion
// valido si los arrays de las otras fuciones me traen valores y demas para poder enviar a insertar en los registros

async function registrarEntrada() {


  try {
    // este fetch se encarga del registro del ususarop
    const usuarios = await fetch("./fetch/registrarEntrada.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: documento,
    });

    const respuestaUsuario = await usuarios.json();
    

    alertaBienvenida.innerHTML =
      respuestaUsuario.mensaje + " " + respuestaUsuario.id_documento;

    alerta.classList.add("aparecer");
    alerta.style.background = respuestaUsuario.color;
    setTimeout(() => {
      alerta.classList.remove("aparecer");
    }, 3000);
    // ------------------------------------------------------------------

    // este fetch se va a encargar del registro de los objetos tambien, estoy enviando un objeto para poder identificar el array y a quien se le va a asignar

    const Objeto = { 
      array: entradaObjetos, 
      documento: documento,
      observaciones: obtenerObservacionesFuncion(),  
      };

    

    
      const objetos = await fetch("./fetch/registrarObjetos.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(Objeto),
      });
      

      const respuesta = await objetos.json();
      

    

    const objetoVehiculo = {
      arrayVehiculos: entradavehiculos,
      documento: documento,
      observaciones: obtenerObservacionesFuncion(),  
    };

    
      const vehiculo = await fetch("./fetch/registrarVehiculo.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(objetoVehiculo),
      });
      

      const respuestaVehiculo = await vehiculo.json();
      
    



  } catch (error) {
    
  }
  finally{
    // aqui se vacian los arrays para que no se repitan los datos
    entradaObjetos = [];
    entradavehiculos = [];
    limpiarObservaciones() // esta funcion esta exportada del archivo "modalObservacion" lo que hace es vaciar el array 
    
    cerrar();
  }
}

const selecionarObjetos = document.getElementById("selecionarObjetos") 
function cerrar(){
    selecionarObjetos.classList.add("desaparecerModal")
    selecionarObjetos.classList.remove("aparecerModal")
}

// todo esto es para la parte del qr y lo que se necesita para que funcione
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const resultDiv = document.getElementById("result");
const startButton = document.getElementById("startButton");
const stopButton = document.getElementById("stopButton");
const videoContainer = document.getElementById("videoContainer");

let stream = null;
let scanning = false;

// Contexto para el canvas
const canvasContext = canvas.getContext("2d");

// Función para iniciar la cámara
async function startCamera() {
  try {
    // detener camara caso tal que este abierto algo p'or otro lao
    if (stream) {
      stream.getTracks().forEach((track) => track.stop());
    }

    // para que tome los accesos
    stream = await navigator.mediaDevices.getUserMedia({
      video: {
        facingMode: "environment", // esto es para camara trasera de los celu
        width: {
          ideal: 1280,
        },
        height: {
          ideal: 720,
        },
      },
      audio: false,
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
    stream.getTracks().forEach((track) => track.stop());
    stream = null;
  }

  video.srcObject = null;
  scanning = false;

  startButton.disabled = false;
  stopButton.disabled = true;

  resultDiv.textContent = " Haz clic en 'Iniciar Cámara'.";
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
    const imageData = canvasContext.getImageData(
      0,
      0,
      canvas.width,
      canvas.height
    );

    // Escanear el código QR
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
      inversionAttempts: "dontInvert",
    });

    // Si se encontró un código QR
    if (code) {
      const regex = /^\d{8,}$/;

    

      if (regex.test(code.data)) {
        stopCamera();
        videoContainer.style.background = "green";
        setTimeout(() => {
          startCamera();
        }, 7);

        async function datosUsuario() {
          const datos = await fetch("./fetch/consultaParaModal.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: code.data,
          });

          //   contedores aqui van declarados
          let informacionUsuario =
            document.getElementById("informacionUsuario");
          let contenedorObjetos = document.getElementById("contenedorObjetos");
          let contenedorVehiculos = document.getElementById(
            "contenedorVehiculos"
          );

          // ----------------------------------------------------------------------------------------

          // declaracion de variables segun la respuesta del documento ./fetch/consultaáraModal
          let respuesta = await datos.json();
          let info = respuesta.datosPersonales;
          let objetosRespuesta = respuesta.objetos;
          let vehiculosRespuesta = respuesta.vehiculos;

          

          //   vacio los contenedores antes de (por si las moscas)
          informacionUsuario.innerHTML = "";
          contenedorObjetos.innerHTML = "";
          contenedorVehiculos.innerHTML = "";

          // -------------------------------------------------------

          informacionUsuario.innerHTML += `
          <div onclick="cerrar()" class="divCerrar"><span class="cerrar" >X</span></div> 
                    <img src="../../uploads/${info.foto}" alt="Foto de perfil del usuario" class="fotoDePerfil">

                    <b style="font-size:1.5rem;margin-bottom:30px;">Datos Del Usuario</b>

                    <p><b> Nombre Usuario:</b> <br> ${info.nombre_completo} </p>
                    <p><b> Documento:</b> <br> ${info.id_documento} </p>
                    <p><b> Correo Electronico: </b> <br> ${info.correo} </p>
                    <p><b> Rol Del Usuario:</b> <br> ${info.nombre_rol} </p>
                    <input type="submit" id="botonRegistrarEntrada" value="${info.id_estado === 1 ? 'Registrar Entrada' : 'Registrar Salida'}" class="submit-btn">`;

          const botonRegistrarEntrada = document.getElementById("botonRegistrarEntrada" );
          if (botonRegistrarEntrada) {
            botonRegistrarEntrada.addEventListener("click", registrarEntrada);
          }

          if (objetosRespuesta.length == 0) {
            contenedorObjetos.innerHTML = "";
          }

          if (objetosRespuesta.length > 0) {
            objetosRespuesta.forEach(function (objeto) {
              contenedorObjetos.innerHTML += `

            <div class="tarjetaObjeto" data-id="${objeto.id_serial}" >
            <div class="divFoto">
                                            <p>${objeto.id_serial}</p>
                                            <img src="../../uploads/${(objeto.objetoFoto) ?? 'default_iconos.png'}" width="80px" height="80px" alt="objeto">
                                        </div>
                                        
                                        <div class="divCaracteristicas">
                                            <h4>${objeto.nombre}</h4>
                                            <p style="font-size: 0.8rem">${objeto.caracteristica}</p>
                                        </div>

                                        <div class="divcategorias">
                                            <b class="tituloCategoria">Marca:</b>
                                            <p class="tipoObjeto">${objeto.nombre_marca}</p>
                                            <b class="tituloCategoria">Estado:</b>
                                            <p class="tipoObjeto">${objeto.estado}</p>
                                            <b class="tituloCategoria">Tipo:</b>
                                            <p class="tipoObjeto">${objeto.tipoObjeto}</p>
                                            </div>
                                            </div>`;
            });

            document.querySelectorAll(".tarjetaObjeto").forEach((tarjeta) => {
              tarjeta.addEventListener("click", () => {
                toggleSelection(tarjeta);
              });
            });
          }

          // aqui se llama la funcion para hacer que ella cree los botones dependiendo de la cantidad de tajetas que encuentre esto es para los objetos
          agregarBotonesObservacion();
          

          // aqui se evalua el contenedor de vehiculos si es 0 lo que devuelve el json pues se vacia sino entonces imprime

          if (vehiculosRespuesta.length > 0) {
            vehiculosRespuesta.forEach(function (vehiculo) {
              contenedorVehiculos.innerHTML += `
            
               <div class="tarjeticaVehiculo" data-id="${vehiculo.id_placa}" >
                                            <div class="divFoto">
                                            <p>${vehiculo.id_placa}</p>
                                                <img src="../../uploads/${vehiculo.foto}" width="80px" height="80px" alt="objeto">
                                            </div>

                                            <div style="display: flex; flex-direction:column; justify-content:center; width:150px; margin-left:20px;">
                                                <p>Carateristica Del Vehiculo:</p>
                                                <h4>${vehiculo.caracteristicas}</h4>

                                            </div>

                                            <div>
                                                <b class="tituloCategoria">Marca:</b>
                                                <p class="tipoObjeto">${vehiculo.nombre_marca} </p>
                                                <b class="tituloCategoria">Estado:</b>
                                                <p class="tipoObjeto"> ${vehiculo.estado} </p>
                                                <b class="tituloCategoria">Tipo:</b>
                                                <p class="tipoObjeto">${vehiculo.tipoVehiculo} </p>
                                            </div>
                                        </div>
                                  
            
            `;
            });

            if (vehiculosRespuesta.length == 0) {
              contenedorVehiculos.innerHTML = "";
            }

            document.querySelectorAll(".tarjeticaVehiculo").forEach((tarjeta) => {
                tarjeta.addEventListener("click", () => {
                  toggleSelectionVehiculo(tarjeta);
                });
              });

              agregarBotonesVehiculos(); 
          }
        }

        datosUsuario();

        const selecionarObjetos = document.getElementById("selecionarObjetos");
        selecionarObjetos.classList.add("aparecerModal");
        selecionarObjetos.classList.remove("desaparecerModal");

        documento = code.data;
        return documento;
      } else {
        stopCamera();
        videoContainer.style.background = "red";
        setTimeout(() => {
          alert(
            "Alerta codigo qr no valido! Valor del qr ingresado -> :  " +
              code.data
          );
        }, 500);
      }
    }
  }

  if (scanning) {
    setTimeout(() => {
      videoContainer.style.opacity = 1;
      requestAnimationFrame(scanQR);
    }, 2000);
  }
}

// Event listeners para los botones
startButton.addEventListener("click", startCamera);
stopButton.addEventListener("click", stopCamera);

// Detener la cámara al salir de la página
window.addEventListener("beforeunload", stopCamera);

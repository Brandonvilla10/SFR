


  // todo lo que uso para el modal
  const modalObservaciones = document.getElementById("modalObservaciones");
  const cerrarModalBtn = document.getElementById("cerrarModalObservaciones");
  const guardarBtn = document.getElementById("guardarObservacion");
  const cancelarBtn = document.getElementById("cancelarObservacion");
  const textoObservacion = document.getElementById("textoObservacion");

  let objetoSeleccionadoId = null;

  // Función para abrir el modal
  function abrirModalObservaciones(id, observacionExistente = "") {
    objetoSeleccionadoId = id;
    textoObservacion.value = observacionExistente;
    modalObservaciones.classList.remove("desaparecerModal");
    modalObservaciones.classList.add("aparecerModal");
  }

  // Función para cerrar el modal
  function cerrarModalObservaciones() {
    modalObservaciones.classList.remove("aparecerModal");
    modalObservaciones.classList.add("desaparecerModal");
    textoObservacion.value = "";
    objetoSeleccionadoId = null;
  }

  
let observacionExportada = [];

  // Guardar observación
  guardarBtn.addEventListener("click", function () {
    if (objetoSeleccionadoId) {
      const observacion = textoObservacion.value.trim();

      console.log(
        "Guardando observación para el objeto ID:",
        objetoSeleccionadoId
      );
      console.log("Texto:", observacion);

      observacionExportada.push({
        id: objetoSeleccionadoId,
        observacion: observacion,
      })

      console.log(observacionExportada);
      cerrarModalObservaciones();
    }
  });


  export function obtenerObservacionesFuncion() {
    return observacionExportada;
  }

  // Eventos para cerrar el modal
  cerrarModalBtn.addEventListener("click", cerrarModalObservaciones);
  cancelarBtn.addEventListener("click", cerrarModalObservaciones);

  // Cerrar el modal si se hace clic fuera de él
  modalObservaciones.addEventListener("click", function (e) {
    if (e.target === modalObservaciones) {
      cerrarModalObservaciones();
    }
  });

  
  
  



  // agregar los botones de observación 
export function agregarBotonesObservacion() {
    const tarjetas = document.querySelectorAll(".tarjetaObjeto");

    tarjetas.forEach((tarjeta) => {
      const id = tarjeta.getAttribute("data-id");

      // Verificar si ya tiene un botón de observación
      if (!tarjeta.querySelector(".botonObservacion")) {
        const boton = document.createElement("button");
        boton.className = "botonObservacion";
        boton.textContent = "Observaciones";
        boton.onclick = function (e) {
          e.stopPropagation(); 

          abrirModalObservaciones(id);
        };

        tarjeta.appendChild(boton);
      }
    });
  }
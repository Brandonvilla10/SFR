  // todo lo que uso para el modal
  const modalObservaciones = document.getElementById("modalObservaciones");
  const cerrarModalBtn = document.getElementById("cerrarModalObservaciones");
  const guardarBtn = document.getElementById("guardarObservacion");
  const cancelarBtn = document.getElementById("cancelarObservacion");
  const textoObservacion = document.getElementById("textoObservacion");

  let objetoSeleccionadoId = null;

  // esta función abre el modal de observaciones
  // se le pasa el id del objeto y si ya había texto se pone también
  function abrirModalObservaciones(id, observacionExistente = "") {
    objetoSeleccionadoId = id;
    textoObservacion.value = observacionExistente;
    modalObservaciones.classList.remove("desaparecerModal");
    modalObservaciones.classList.add("aparecerModal");
  }

  // esta cierra el modal, limpia el texto y el id seleccionado
  function cerrarModalObservaciones() {
    modalObservaciones.classList.remove("aparecerModal");
    modalObservaciones.classList.add("desaparecerModal");
    textoObservacion.value = "";
    objetoSeleccionadoId = null;
  }

  
let observacionExportada = [];

  // cuando se da clic en guardar, se guarda la observación escrita
  // y se mete al array con su id, después se cierra el modal
  guardarBtn.addEventListener("click", function () {
    if (objetoSeleccionadoId) {
      const observacion = textoObservacion.value.trim();
      observacionExportada.push({
        id: objetoSeleccionadoId,
        observacion: observacion,
      })
      console.log(observacionExportada)
      cerrarModalObservaciones();
    }
  });

  // esta función exporta lo que se haya guardado
  export function obtenerObservacionesFuncion() {
    return observacionExportada;
  }

  // esta borra todas las observaciones que se hayan guardado
  export function limpiarObservaciones() {
    return observacionExportada = [];
  }

  // estos son los eventos para cerrar el modal cuando se le da clic en cerrar o cancelar
  cerrarModalBtn.addEventListener("click", cerrarModalObservaciones);
  cancelarBtn.addEventListener("click", cerrarModalObservaciones);

  // esto permite cerrar el modal si se hace clic fuera del contenido
  modalObservaciones.addEventListener("click", function (e) {
    if (e.target === modalObservaciones) {
      cerrarModalObservaciones();
    }
  });

  
  // esta función agrega botones de observación a cada tarjeta de objeto
  // si no tiene ya uno, se lo agrega
export function agregarBotonesObservacion() {
    const tarjetas = document.querySelectorAll(".tarjetaObjeto");

    tarjetas.forEach((tarjeta) => {
      const id = tarjeta.getAttribute("data-id");

      // si la tarjeta no tiene botón de observación, se lo pone
      const yaTiene = tarjeta.querySelector(".botonObservacion");
      if (!yaTiene) {
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

  // esta hace lo mismo que la anterior pero con tarjetas de vehículos
export function agregarBotonesVehiculos(){
  const tarjeta = document.querySelectorAll(".tarjeticaVehiculo");

  tarjeta.forEach((tarjeta) => {
    const id = tarjeta.getAttribute("data-id");

    if(!tarjeta.querySelector(".botonObservacion")){
      const boton = document.createElement("button");
      boton.className = "botonObservacion";
      boton.textContent = "Observaciones";
      boton.onclick = function (e) {
        e.stopPropagation();
        abrirModalObservaciones(id);
      };

      tarjeta.appendChild(boton);
    }
  })
}

const homeAzul = document.getElementById("homeAzul")
const laptopBlanco = document.getElementById("laptopBlanco")
const carroBlanco = document.getElementById("carroBlanco")
const escanearBlanco = document.getElementById("escanearBlanco")
const calendarioBlanco = document.getElementById("calendarioBlanco")
const danielBlanco = document.getElementById("danielBlanco")


let gota = document.getElementById("gota")

function manejadorEvento(elemento,direccion,src = null){
    elemento.addEventListener("click",(e) => {
        e.preventDefault()
        gota.style.left = direccion
        if(src){
            elemento.src = src
        }
    })
}

// tengo que resetear todos los elementos a blanco para despues pasarlo a azul recuerdoo

manejadorEvento(homeAzul, "10px", "iconSideBar/homeAzul.png");
manejadorEvento(laptopBlanco, "63px", "iconSideBar/laptopAzul.png");
manejadorEvento(carroBlanco, "114px", "iconSideBar/carroAzul.png");
manejadorEvento(escanearBlanco, "161px", "iconSideBar/escanearAzul.png");
manejadorEvento(calendarioBlanco, "214px", "iconSideBar/calendarioAzul.png");
manejadorEvento(danielBlanco, "268px", "iconSideBar/danielAzul.png");

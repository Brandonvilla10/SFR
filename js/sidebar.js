const imagenLaptopAzul = document.getElementById("imagenLaptopAzul")

imagenLaptopAzul.style.display = "none";

const botonOrdenador = document.getElementById("botonOrdenador")

botonOrdenador.addEventListener("click", function (e) {
    e.preventDefault();
    const gota = document.querySelector(".gota");
    
    
    gota.style.top = "39%";
    gota.style.left = "47px"; 

   
    const imagen = botonOrdenador.querySelector("img")

    imagen.classList.add("oculto")
    setTimeout(() => {
        imagen.src = "iconSideBar/ordenador-portatil (2).png"
        imagen.classList.remove("oculto")
        imagen.classList.add("visible")
    }, 500);
    
});

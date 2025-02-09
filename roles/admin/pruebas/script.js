const homeAzul = document.getElementById("homeAzul")
const laptopBlanco = document.getElementById("laptopBlanco")
const usuarioBlanco = document.getElementById("usuarioBlanco")
const escanearBlanco = document.getElementById("escanearBlanco")
const calendarioBlanco = document.getElementById("calendarioBlanco")
const danielBlanco = document.getElementById("danielBlanco")

const iconos = [
    { elemento: homeAzul, azul: "pruebas/iconSideBar/homeAzul.png", blanco: "pruebas/iconSideBar/homeBlanco.png" },
    { elemento: laptopBlanco, azul: "pruebas/iconSideBar/laptopAzul.png", blanco: "pruebas/iconSideBar/laptopBlanco.png" },
    { elemento: usuarioBlanco, azul: "pruebas/iconSideBar/usuarioAzul.png", blanco: "pruebas/iconSideBar/usuarioBlanco.png" },
    { elemento: escanearBlanco, azul: "pruebas/iconSideBar/escanearAzul.png", blanco: "pruebas/iconSideBar/escanearBlanco.png" },
    { elemento: calendarioBlanco, azul: "pruebas/iconSideBar/calendarioAzul.png.png", blanco: "pruebas/iconSideBar/calendarioBlanco.png" },
];

function resetearIconos() {
    iconos.forEach((icono) => {
        icono.elemento.src = icono.blanco; 
    });
}



let rutaActual = window.location.pathname;
let gota = document.getElementById("gota")


if(rutaActual === "/ClonSFR/SFR/roles/admin/home.php"){
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const posicionElemento = homeAzul.getBoundingClientRect();
    gota.style.top = `${posicionElemento.top + scrollTop - 25}px`;
    resetearIconos();
    homeAzul.src = "pruebas/iconSideBar/homeAzul.png"
}


if(rutaActual === "/ClonSFR/SFR/roles/admin/buscarUsuario.php"){
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const posicionElemento = usuarioBlanco.getBoundingClientRect();
    gota.style.top = `${posicionElemento.top + scrollTop - 28}px`;
    resetearIconos();
    usuarioBlanco.src = "pruebas/iconSideBar/usuarioAzul.png";
}


if(rutaActual === "/ClonSFR/SFR/roles/admin/daniel.php"){
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const posicionElemento = laptopBlanco.getBoundingClientRect();
    gota.style.top = `${posicionElemento.top + scrollTop - 35}px`;
    resetearIconos();
    laptopBlanco.src = "pruebas/iconSideBar/laptopAzul.png";
}


function redireccionPagina(pagina){
    if (pagina == "home.php" ){
        setTimeout(() => {
            location.href = "/ClonSFR/SFR/roles/admin/home.php" ;
        },1000);
    }else{
        setTimeout(() => {
            location.href = "/ClonSFR/SFR/roles/admin/" + pagina;
        },);

    }
}



    function manejadorEvento(elemento,src = null){
        elemento.addEventListener("click",(e) => {
            resetearIconos()


            const rect1 = elemento.getBoundingClientRect();
            gota.style.left = `${rect1.left-7}px`;

            if(src){
                elemento.src = src
            }
        })
    }

// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// En esta seccion separo las funciones dependiendo de la direccion a la que funciona osea hacia donde dirije la gota
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::



// ------------------------------------------------------------------------------------------------------------
function manejadorEventoPG(elemento, src = null, correccionElse = 0) {
    elemento.addEventListener("click", (e) => {
        e.preventDefault();
        
        if(elemento == homeAzul) {
            redireccionPagina("home.php");

        }else if (elemento == laptopBlanco) {
            redireccionPagina("daniel.php");
            
        }else if(elemento == usuarioBlanco){
            redireccionPagina("buscarUsuario.php")
        }   
        resetearIconos();
        
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::        
        const rect1 = elemento.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (window.innerHeight > 820 && window.innerHeight < 1120) {
            const rect1 = elemento.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            gota.style.top = `${rect1.top + scrollTop - 50}px`;

        }else if (window.innerHeight > 1120) {
            const rect1 = elemento.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            gota.style.top = `${rect1.top + scrollTop - 53}px`;

        }  else {
        gota.style.top = `${rect1.top + scrollTop - correccionElse}px`;
        }
        
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
         
        
 // :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::       
        
        if (src) {
            elemento.src = src;
        }
    });
}
//  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::    

  


    const width = window.innerWidth

    if(width >= 320 && width < 768 ){

        manejadorEvento(homeAzul, "pruebas/iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "pruebas/iconSideBar/laptopAzul.png");
        manejadorEvento(usuarioBlanco,  "pruebas/iconSideBar/usuarioAzul.png");
        manejadorEvento(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png");
        
        
    }else if(width >= 768 && width <= 1869 ){
        manejadorEventoPG(homeAzul, "pruebas/iconSideBar/homeAzul.png",24.5);
        manejadorEventoPG(laptopBlanco,  "pruebas/iconSideBar/laptopAzul.png",32);
        manejadorEventoPG(usuarioBlanco,  "pruebas/iconSideBar/usuarioAzul.png",32);
        manejadorEventoPG(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png",32);
        manejadorEventoPG(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png",32);

    }else if(width >= 1870){
        manejadorEventoPG(homeAzul, "pruebas/iconSideBar/homeAzul.png",32);
        manejadorEventoPG(laptopBlanco,  "pruebas/iconSideBar/laptopAzul.png",32);
        manejadorEventoPG(usuarioBlanco,  "pruebas/iconSideBar/usuarioAzul.png",32);
        manejadorEventoPG(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png",40);
        manejadorEventoPG(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png",32);
    }
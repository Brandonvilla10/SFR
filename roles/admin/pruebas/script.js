const homeAzul = document.getElementById("homeAzul")
const laptopBlanco = document.getElementById("laptopBlanco")
const carroBlanco = document.getElementById("carroBlanco")
const escanearBlanco = document.getElementById("escanearBlanco")
const calendarioBlanco = document.getElementById("calendarioBlanco")
const danielBlanco = document.getElementById("danielBlanco")


let gota = document.getElementById("gota")



    function manejadorEvento(elemento,src = null){
        elemento.addEventListener("click",(e) => {
            e.preventDefault()
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
const height = window.innerHeight
    function manejadorEventoPG(elemento,src = null,correccionElse){
        elemento.addEventListener("click",(e) => {
            e.preventDefault()

            resetearIconos()

            const rect1 = elemento.getBoundingClientRect();

            
            gota.style.top = `${rect1.top-correccionElse}px`;
            
            
            if(src){
                elemento.src = src
            }
        })
    }
//  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::    

    const iconos = [
        { elemento: homeAzul, azul: "pruebas/iconSideBar/homeAzul.png", blanco: "pruebas/iconSideBar/homeBlanco.png" },
        { elemento: laptopBlanco, azul: "pruebas/iconSideBar/laptopAzul.png", blanco: "pruebas/iconSideBar/laptopBlanco.png" },
        { elemento: carroBlanco, azul: "pruebas/iconSideBar/carroAzul.png", blanco: "pruebas/iconSideBar/carroBlanco.png" },
        { elemento: escanearBlanco, azul: "pruebas/iconSideBar/escanearAzul.png", blanco: "pruebas/iconSideBar/escanearBlanco.png" },
        { elemento: calendarioBlanco, azul: "pruebas/iconSideBar/calendarioAzul.png.png", blanco: "pruebas/iconSideBar/calendarioBlanco.png" },
    ];
    
    function resetearIconos() {
        iconos.forEach((icono) => {
            icono.elemento.src = icono.blanco; 
        });
    }


    const width = window.innerWidth

    if(width >= 320 && width < 768 ){

        manejadorEvento(homeAzul, "pruebas/iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "pruebas/iconSideBar/laptopAzul.png");
        manejadorEvento(carroBlanco,  "pruebas/iconSideBar/carroAzul.png");
        manejadorEvento(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png");
        
        
    }else if(width >= 768 && width <= 1869 ){
        manejadorEventoPG(homeAzul, "pruebas/iconSideBar/homeAzul.png",22);
        manejadorEventoPG(laptopBlanco,  "pruebas/iconSideBar/laptopAzul.png",22);
        manejadorEventoPG(carroBlanco,  "pruebas/iconSideBar/carroAzul.png",22);
        manejadorEventoPG(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png",22);
        manejadorEventoPG(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png",23);

    }else if(width >= 1870){
        manejadorEventoPG(homeAzul, "pruebas/iconSideBar/homeAzul.png",27);
        manejadorEventoPG(laptopBlanco,  "pruebas/iconSideBar/laptopAzul.png",27);
        manejadorEventoPG(carroBlanco,  "pruebas/iconSideBar/carroAzul.png",27);
        manejadorEventoPG(escanearBlanco,  "pruebas/iconSideBar/escanearAzul.png",27);
        manejadorEventoPG(calendarioBlanco,  "pruebas/iconSideBar/calendarioAzul.png",27);
    }
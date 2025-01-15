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
    function manejadorEventoPG(elemento,src = null){
        elemento.addEventListener("click",(e) => {
            e.preventDefault()

            resetearIconos()

            const rect1 = elemento.getBoundingClientRect();
            gota.style.top = `${rect1.top-48}px`
            
            if(src){
                elemento.src = src
            }
        })
    }
//  :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::    

    const iconos = [
        { elemento: homeAzul, azul: "iconSideBar/homeAzul.png", blanco: "iconSideBar/homeBlanco.png" },
        { elemento: laptopBlanco, azul: "iconSideBar/laptopAzul.png", blanco: "iconSideBar/laptopBlanco.png" },
        { elemento: carroBlanco, azul: "iconSideBar/carroAzul.png", blanco: "iconSideBar/carroBlanco.png" },
        { elemento: escanearBlanco, azul: "iconSideBar/escanearAzul.png", blanco: "iconSideBar/escanearBlanco.png" },
        { elemento: calendarioBlanco, azul: "iconSideBar/calendarioAzul.png.png", blanco: "iconSideBar/calendarioBlanco.png" },
    ];
    
    function resetearIconos() {
        iconos.forEach((icono) => {
            icono.elemento.src = icono.blanco; 
        });
    }


    const width = window.innerWidth

    if(width >= 320 && width < 768 ){

        manejadorEvento(homeAzul, "iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "iconSideBar/laptopAzul.png");
        manejadorEvento(carroBlanco,  "iconSideBar/carroAzul.png");
        manejadorEvento(escanearBlanco,  "iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco,  "iconSideBar/calendarioAzul.png");
        
        
    }else if(width >= 768 ){
        manejadorEventoPG(homeAzul, "iconSideBar/homeAzul.png");
        manejadorEventoPG(laptopBlanco,  "iconSideBar/laptopAzul.png");
        manejadorEventoPG(carroBlanco,  "iconSideBar/carroAzul.png");
        manejadorEventoPG(escanearBlanco,  "iconSideBar/escanearAzul.png");
        manejadorEventoPG(calendarioBlanco,  "iconSideBar/calendarioAzul.png");

    }
    


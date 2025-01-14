const homeAzul = document.getElementById("homeAzul")
const laptopBlanco = document.getElementById("laptopBlanco")
const carroBlanco = document.getElementById("carroBlanco")
const escanearBlanco = document.getElementById("escanearBlanco")
const calendarioBlanco = document.getElementById("calendarioBlanco")
const danielBlanco = document.getElementById("danielBlanco")


let gota = document.getElementById("gota")



    const width = window.innerWidth
    function manejadorEvento(elemento,direccion,src = null){
        elemento.addEventListener("click",(e) => {
            e.preventDefault()
            resetearIconos()
            gota.style.left = direccion
            
            if(src){
                elemento.src = src
            }
        })
    }

// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// En esta seccion separo las funciones dependiendo de la direccion a la que funciona osea hacia donde dirije la gota
// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    function manejadorEventoPG(elemento,direccion,src = null){
        elemento.addEventListener("click",(e) => {
            e.preventDefault()
            resetearIconos()
            gota.style.top = direccion
            
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
        { elemento: danielBlanco, azul: "iconSideBar/danielAzul.png", blanco: "iconSideBar/danielBlanco.png" },
    ];
    
    function resetearIconos() {
        iconos.forEach((icono) => {
            icono.elemento.src = icono.blanco; 
        });
    }

    if(width >= 320 && width < 375 ){

        manejadorEvento(homeAzul, "10px", "iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "63px", "iconSideBar/laptopAzul.png");
        manejadorEvento(carroBlanco, "114px", "iconSideBar/carroAzul.png");
        manejadorEvento(escanearBlanco, "161px", "iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco, "214px", "iconSideBar/calendarioAzul.png");
        manejadorEvento(danielBlanco, "268px", "iconSideBar/danielAzul.png");
        
    }else if(width >= 375 && width < 425){

        manejadorEvento(homeAzul, "10px", "iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "68px", "iconSideBar/laptopAzul.png");
        manejadorEvento(carroBlanco, "126px", "iconSideBar/carroAzul.png");
        manejadorEvento(escanearBlanco, "182px", "iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco, "241px", "iconSideBar/calendarioAzul.png");
        manejadorEvento(danielBlanco, "300px", "iconSideBar/danielAzul.png");

    }else if(width >= 425 && width < 768){

        manejadorEvento(homeAzul, "10px", "iconSideBar/homeAzul.png");
        manejadorEvento(laptopBlanco, "82px", "iconSideBar/laptopAzul.png");
        manejadorEvento(carroBlanco, "151px", "iconSideBar/carroAzul.png");
        manejadorEvento(escanearBlanco, "222px", "iconSideBar/escanearAzul.png");
        manejadorEvento(calendarioBlanco, "292px", "iconSideBar/calendarioAzul.png");
        manejadorEvento(danielBlanco, "361px", "iconSideBar/danielAzul.png");
    }else if(width >= 768 && width < 1536){
        manejadorEventoPG(homeAzul, "4%", "iconSideBar/homeAzul.png");
        manejadorEventoPG(laptopBlanco, "30%", "iconSideBar/laptopAzul.png");
        manejadorEventoPG(carroBlanco, "43%", "iconSideBar/carroAzul.png");
        manejadorEventoPG(escanearBlanco, "55%", "iconSideBar/escanearAzul.png");
        manejadorEventoPG(calendarioBlanco, "68%", "iconSideBar/calendarioAzul.png");
        manejadorEventoPG(danielBlanco, "81%", "iconSideBar/danielAzul.png");
    }else if(width >= 1536){
        manejadorEventoPG(homeAzul, "4%", "iconSideBar/homeAzul.png");
        manejadorEventoPG(laptopBlanco, "27%", "iconSideBar/laptopAzul.png");
        manejadorEventoPG(carroBlanco, "40%", "iconSideBar/carroAzul.png");
        manejadorEventoPG(escanearBlanco, "52%", "iconSideBar/escanearAzul.png");
        manejadorEventoPG(calendarioBlanco, "64%", "iconSideBar/calendarioAzul.png");
        manejadorEventoPG(danielBlanco, "78%", "iconSideBar/danielAzul.png");
    }
    


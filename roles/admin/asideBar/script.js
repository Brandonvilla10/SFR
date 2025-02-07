let aside = document.getElementById("barraLateral");
let texto = document.querySelectorAll(".seccionTexto");
let seccion = document.querySelectorAll(".seccion");
let fotoPersona = document.getElementById("iconoPersona");




if(window.innerWidth >= 320 && window.innerWidth <= 720){

    const hamburguesa = document.getElementById('abrir');
    const barralateral = document.getElementById('barraLateral');
    const enlaces = document.querySelectorAll('a'); 
    
    enlaces.forEach(enlace => {
            enlace.style.pointerEvents = 'none' 
        });
    
    
    
    hamburguesa.addEventListener("click", () => {
        barralateral.classList.toggle("dentro");
        barralateral.style.backgroundColor = "#63AEE8";
    
        if(barralateral.classList.contains("dentro")){
            enlaces.forEach(enlace => {
                enlace.style.pointerEvents = 'auto' 
                
            });
            texto.forEach((element) => {
                element.style.visibility = "hidden"; 
                element.style.display = "none"; 
            });
    
        }else if(barralateral.classList.contains("fuera")){
            enlaces.forEach(enlace => {
                enlace.style.pointerEvents = 'none' 
                
             });
    
             texto.forEach((element) => {
                element.style.visibility = "hidden"; 
                element.style.display = "none"; 
            });
        }
    });
    

}    



if(window.innerWidth >= 768){
aside.addEventListener("mouseover", () => {
    texto.forEach((element) => {
        element.style.visibility = "visible"; 
        element.style.display = "block"; 
    });
    
    fotoPersona.style.height = "100px";
    fotoPersona.style.width = "80px";
    seccion.forEach((element) =>{
        element.style.justifyContent = "left";
    })
});


aside.addEventListener("mouseout", () => {
    texto.forEach((element) => {
        element.style.visibility = "hidden"; 
        element.style.display = "none"; 
    });

    fotoPersona.style.height = "50px";
    fotoPersona.style.width = "50px";

    seccion.forEach((element) =>{
        element.style.justifyContent = "center";
    })
});


}


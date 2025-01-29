let aside = document.getElementById("barraLateral");
let texto = document.querySelectorAll(".seccionTexto");
let seccion = document.querySelectorAll(".seccion");
let fotoPersona = document.getElementById("iconoPersona");




if(window.innerWidth > 768){
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
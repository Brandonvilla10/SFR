


let botonHomeAzul = document.getElementById("botonHomeAzul");
let botonHomeblanco = document.getElementById("botonHomeblanco");

let botonLaptopAzul = document.getElementById("botonLaptopAzul");
let botonOrdenador = document.getElementById("botonLaptopBlanco");


let carroBlanco = document.getElementById("carroBlanco")
let carroAzul = document.getElementById("carroAzul")

let usuarioBlanco = document.getElementById("usuarioBlanco")
let usuarioAzul = document.getElementById("usuarioAzul")

const gota = document.querySelector(".gota");


function desaparecer(variable) {
    variable.style.visibility = "hidden";
}

function aparecer(variable) {
    variable.style.visibility = "visible";
}

botonHomeblanco.addEventListener("click", (e) => {
    e.preventDefault();
    gota.style.top = "2%";
    gota.style.left = "47px";
    aparecer(botonOrdenador)
    aparecer(botonHomeAzul)
    aparecer(carroBlanco)
    aparecer(usuarioBlanco)
    desaparecer(botonHomeblanco)
    desaparecer(botonLaptopAzul)
    desaparecer(carroAzul)
    desaparecer(usuarioAzul)



});
botonOrdenador.addEventListener("click", (e) => {
    e.preventDefault();
    gota.style.top = "39%";
    gota.style.left = "47px";
    aparecer(botonLaptopAzul)
    aparecer(botonHomeblanco) 
    aparecer(carroBlanco)
    aparecer(usuarioBlanco)
    desaparecer(botonOrdenador)
    desaparecer(botonHomeAzul)
    desaparecer(carroAzul)
    desaparecer(usuarioAzul)

});

carroBlanco.addEventListener("click",(e) =>{
    e.preventDefault();
    gota.style.top = "52%";
    gota.style.left = "47px";
    aparecer(carroAzul)
    aparecer(botonHomeblanco) 
    aparecer(botonOrdenador)
    aparecer(usuarioBlanco)
    desaparecer(botonLaptopAzul)
    desaparecer(botonHomeAzul)
    desaparecer(carroBlanco)
    desaparecer(usuarioAzul)
})

usuarioBlanco.addEventListener("click",(e) => {
    e.preventDefault()
    gota.style.top = "65%";
    gota.style.left = "47px";
    aparecer(botonHomeblanco) 
    aparecer(botonOrdenador)
    aparecer(usuarioAzul)
    aparecer(carroBlanco)
    desaparecer(botonLaptopAzul)
    desaparecer(botonHomeAzul)
    desaparecer(carroAzul)
    desaparecer(usuarioBlanco)
})
botonHomeAzul.addEventListener("click",(e)=>{e.preventDefault()})
botonLaptopAzul.addEventListener("click",(e)=>{e.preventDefault()})
carroAzul.addEventListener("click",(e)=>{e.preventDefault()})
usuarioAzul.addEventListener("click",(e)=>{e.preventDefault()})
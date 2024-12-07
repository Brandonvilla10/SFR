


let botonHomeAzul = document.getElementById("botonHomeAzul");
let botonHomeblanco = document.getElementById("botonHomeblanco");

let botonLaptopAzul = document.getElementById("botonLaptopAzul");
let botonOrdenador = document.getElementById("botonLaptopBlanco");


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
    desaparecer(botonHomeblanco)


});
botonOrdenador.addEventListener("click", (e) => {
    e.preventDefault();
    gota.style.top = "39%";
    gota.style.left = "47px";
    aparecer(botonHomeblanco) 
    aparecer(botonLaptopAzul)
    desaparecer(botonHomeAzul)
    desaparecer(botonOrdenador)

});


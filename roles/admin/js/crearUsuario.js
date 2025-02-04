const botonAgregar = document.getElementById('botonAgregar');
let CrearUsuario = document.getElementById('CrearUsuario');
let textoAbrir = document.getElementById("textoAbrir")
let verUsuarios = document.getElementById("verUsuarios")
const botonTresPuntos = document.getElementById("botonTresPuntos")
let opcionesTresPuntos = document.getElementById("opcionesTresPuntos")

const  botonEliminar = document.getElementById("botonEliminar")
const textoEliminar = document.getElementById("textoEliminar")
const EliminarUsuario = document.getElementById("EliminarUsuario")

botonAgregar.addEventListener('click', () => {
    if (CrearUsuario.classList.contains('mostrar')) {
        CrearUsuario.classList.remove('mostrar');
        setTimeout(() => {
            CrearUsuario.style.display = 'none'; 
        }, 0);
        textoAbrir.innerText = "+"
        verUsuarios.style.opacity = "1"
        botonEliminar.disabled = false
    }else{
        CrearUsuario.style.display = 'flex';
        setTimeout(() => {
            CrearUsuario.classList.add("mostrar")
        }, 0);
        botonEliminar.disabled = true
        textoAbrir.innerText = "x"
        verUsuarios.style.opacity = "10%"
    }
});

botonEliminar.addEventListener('click', () => {
    if (EliminarUsuario.classList.contains('mostrar')) {
        EliminarUsuario.classList.remove('mostrar');
        setTimeout(() => {
            EliminarUsuario.style.display = 'none'; 
        }, 0);
        EliminarUsuario.style.opacity = 0

        botonAgregar.disabled = false
        verUsuarios.style.opacity = "1"
        textoEliminar.innerText = "+"
    }else{
        EliminarUsuario.style.display = 'flex';
        setTimeout(() => {
            EliminarUsuario.classList.add("mostrar")
            EliminarUsuario.style.opacity = 1
        }, 0);
        textoEliminar.innerText = "x"
        botonAgregar.disabled = true
        verUsuarios.style.opacity = "10%"
    }
});


// :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::..

const expresiones = {
    documento: /^\d{6,10}$/,
    nombre: /^[a-zA-ZÀ-ÿ\s]{4,40}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%?&])[A-Za-z\d@#$!%?&]{8,16}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
}


const nombre = document.getElementById("nombre");
const documento = document.getElementById("documento");
const email = document.getElementById("email");
const contraseña = document.getElementById("contraseña");
const confirmarContraseña = document.getElementById("confirmarContraseña");
let registrarCrearUsuario = document.getElementById("registrarCrearUsuario")



registrarCrearUsuario.addEventListener("click",(e)=>{
        
    boolVal = false

    if(!expresiones.nombre.test(nombre.value) || nombre.value == ""){
        boolVal = true
        
    }

    if(!expresiones.correo.test(email.value) || email.value == ""){
        boolVal = true
    }
    if(!expresiones.documento.test(documento.value) || documento.value == ""){
        boolVal = true
    }

    if(!expresiones.password.test(contraseña.value) || contraseña.value == ""){
        boolVal = true
    }

    if(!expresiones.password.test(confirmarContraseña.value) || confirmarContraseña.value == "" && confirmarContraseña.value != contraseña.value){
        boolVal = true
    }
    
    if(boolVal == true){
        e.preventDefault()
    }





})




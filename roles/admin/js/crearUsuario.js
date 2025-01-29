const botonAgregar = document.getElementById('botonAgregar');
let CrearUsuario = document.getElementById('CrearUsuario');
let textoAbrir = document.getElementById("textoAbrir")
let verUsuarios = document.getElementById("verUsuarios")
const botonTresPuntos = document.getElementById("botonTresPuntos")
let opcionesTresPuntos = document.getElementById("opcionesTresPuntos")

botonAgregar.addEventListener('click', () => {
    if (CrearUsuario.classList.contains('mostrar')) {
        CrearUsuario.classList.remove('mostrar');
        setTimeout(() => {
            CrearUsuario.style.display = 'none'; 
        }, 1000);
        textoAbrir.innerText = "+"
        verUsuarios.style.opacity = "1"
    }else{
        CrearUsuario.style.display = 'flex';
        setTimeout(() => {
            CrearUsuario.classList.add("mostrar")
        }, 0);
        textoAbrir.innerText = "x"
        verUsuarios.style.opacity = "10%"
    }
});







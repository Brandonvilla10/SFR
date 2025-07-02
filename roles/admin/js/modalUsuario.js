const modalEliminar = document.getElementById('modalEliminar');
const botonEliminar = document.getElementById('botonEliminar');
const cerrarModalEliminar = document.getElementById('cerrarModalEliminar'); 
const cancelarEliminar = document.getElementById('cancelarEliminar');

const passwordAdmin = document.getElementById('passwordAdmin');
const togglePassword = document.getElementById('togglePassword');

// esto sirve para mostrar o esconder la contraseña del admin al dar clic en el ojito
togglePassword.addEventListener('click', () => {
    if(passwordAdmin.type === 'password'){
        passwordAdmin.type = "text"
        togglePassword.innerHTML = '<i class="bi bi-eye-slash"></i></i>';
    }else{
        passwordAdmin.type = "password"
        togglePassword.innerHTML = ' <i class="bi bi-eye"></i>';
    }
})

// esta función es para cerrar el modal de eliminar
function cerrar(){
    modalEliminar.style.visibility = 'hidden';
    modalEliminar.style.opacity = '0';
}

// cuando se da clic en eliminar, se muestra el modal
botonEliminar.addEventListener('click', () => {
    modalEliminar.style.visibility = 'visible';
    modalEliminar.style.opacity = '1';
});

// cerrar el modal ya sea con la X o con el botón cancelar
cerrarModalEliminar.addEventListener('click', cerrar)
cancelarEliminar.addEventListener('click', cerrar)


// todo esto es para el modal de agregar usuario
const modalAgregar = document.getElementById('modalAgregar');
const contraseñaCrearUsuario = document.getElementById('contraseñaCrearUsuario')
const togglePasswordAgregar = document.getElementById('togglePasswordAgregar')
const cerrarModalAgregar = document.getElementById('cerrarModalAgregar')
const cancelarAgregar = document.getElementById('cancelarAgregar')
const botonAgregar = document.getElementById('botonAgregar')

// esta función cierra el modal de agregar
function cerrarAgregar(){
    modalAgregar.style.visibility = 'hidden';
    modalAgregar.style.opacity = '0';
}

// cuando se da clic en agregar, se muestra el modal
botonAgregar.addEventListener('click', () => {
    modalAgregar.style.visibility = 'visible';
    modalAgregar.style.opacity = '1';
});

// se puede cerrar el modal con la X o con cancelar
cerrarModalAgregar.addEventListener('click', cerrarAgregar)
cancelarAgregar.addEventListener('click', cerrarAgregar)

// esto es para mostrar o esconder la contraseña del nuevo usuario
togglePasswordAgregar.addEventListener('click', () => {
    if(contraseñaCrearUsuario.type === 'password'){
        contraseñaCrearUsuario.type = "text"
        togglePasswordAgregar.innerHTML = '<i class="bi bi-eye-slash"></i></i>';
    }else{
        contraseñaCrearUsuario.type = "password"
        togglePasswordAgregar.innerHTML ='<i class="bi bi-eye"></i>';
    }
})

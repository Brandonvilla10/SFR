const modalEliminar = document.getElementById('modalEliminar');
const botonEliminar = document.getElementById('botonEliminar');
const cerrarModalEliminar = document.getElementById('cerrarModalEliminar'); 
const cancelarEliminar = document.getElementById('cancelarEliminar');

const passwordAdmin = document.getElementById('passwordAdmin');
const togglePassword = document.getElementById('togglePassword');




togglePassword.addEventListener('click', () => {
    if(passwordAdmin.type === 'password'){
        passwordAdmin.type = "text"
        togglePassword.innerHTML = '<i class="bi bi-eye-slash"></i></i>';
    }else{
        passwordAdmin.type = "password"
        togglePassword.innerHTML = ' <i class="bi bi-eye"></i>';
        
    }
})



function cerrar(){
    modalEliminar.style.visibility = 'hidden';
    modalEliminar.style.opacity = '0';
}

botonEliminar.addEventListener('click', () => {
    modalEliminar.style.visibility = 'visible';
    modalEliminar.style.opacity = '1';
});

cerrarModalEliminar.addEventListener('click', cerrar)
cancelarEliminar.addEventListener('click', cerrar)


const modalAgregar = document.getElementById('modalAgregar');
const contraseñaCrearUsuario = document.getElementById('contraseñaCrearUsuario')
const togglePasswordAgregar = document.getElementById('togglePasswordAgregar')
const cerrarModalAgregar = document.getElementById('cerrarModalAgregar')
const cancelarAgregar = document.getElementById('cancelarAgregar')
const botonAgregar = document.getElementById('botonAgregar')


function cerrarAgregar(){
    modalAgregar.style.visibility = 'hidden';
    modalAgregar.style.opacity = '0';
}

botonAgregar.addEventListener('click', () => {
    modalAgregar.style.visibility = 'visible';
    modalAgregar.style.opacity = '1';
});

cerrarModalAgregar.addEventListener('click', cerrarAgregar)
cancelarAgregar.addEventListener('click', cerrarAgregar)

togglePasswordAgregar.addEventListener('click', () => {
    if(contraseñaCrearUsuario.type === 'password'){
        contraseñaCrearUsuario.type = "text"
        togglePasswordAgregar.innerHTML = '<i class="bi bi-eye-slash"></i></i>';
    }else{
        contraseñaCrearUsuario.type = "password"
        togglePasswordAgregar.innerHTML ='<i class="bi bi-eye"></i>';
    }

})
function buscarUsuarios(){
    const buscar = document.getElementById("buscarUsuario").value
    const solicitud = new XMLHttpRequest();

    solicitud.open('GET', `buscar_usuarios.php?buscar=${encodeURIComponent(buscar)}`)

    solicitud.onload = function(){
        if(solicitud.status === 200){
            document.getElementById('tablaUsuarios').innerHTML = solicitud.responseText
        }else{
            console.error("Error No Se Encontro Nada")
        }
    }

    solicitud.send()
}
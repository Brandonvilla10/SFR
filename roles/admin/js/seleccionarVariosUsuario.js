
const boton = document.getElementById("boton")


function validarCheck(){
    const seleccionados = document.querySelectorAll('table input[type="checkbox"]')
    const ArrayCheckBox = [];
    seleccionados.forEach(checbox=>{
        if(checbox.checked){
            if (!ArrayCheckBox.includes(checbox.value))
            ArrayCheckBox.push(checbox.value)
        }
    })

    
    return ArrayCheckBox

}


function enviarDatos(){
    
    const datos = validarCheck();
    if(datos.length > 0){
        
        fetch("./json/usuariosSeleccionados.php",{
        method:"POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos)    
    })
    .then(response => response.json())
    .then(data =>{
        window.location = data.redirect
    })
    .catch(error =>{
        console.log("Ganas de joder de esta vuelta" + error)
    })
    
}else{
    console.log(ArrayCheckBox)
    console.log("Sin Seleccionar usuarios")
}

}





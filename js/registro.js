const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input")

const expresiones = {
    documento: /^\d{6,10}$/,
    nombre: /^[a-zA-ZÀ-ÿ\s]{4,40}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%?&])[A-Za-z\d@#$!%?&]{8,16}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
}

const campos = {
   
    nombreCompleto: false,
    contrasena: false,
    correo: false,
    verify: false
}



const validacion = (e)  =>{

    switch(e.target.name){

        case "nombreCompleto":
            validacionCampos(expresiones.nombre,e.target)
            formBoton();
            break;

        case "documento":
            validacionCampos(expresiones.documento,e.target)
            formBoton();
            break;
        
        case "correo":
            validacionCampos(expresiones.correo,e.target)
            formBoton();
            break;
        
        case "contrasena":
            validacionCampos(expresiones.password,e.target)
            formBoton();
            valContrasena();
            break;
        
        case "verify":
            valContrasena();
            formBoton();
            break;
    }

}

const validacionCampos = (expresion,input) => {

    if(expresion.test(input.value)){

        let errores = document.querySelector(`[name="${input.name}"]`)
        errores.nextElementSibling.classList.remove("errores");
        errores.nextElementSibling.classList.add("invisible");
        input.style.border = "2px solid blue";
        campos[input.name] = true;

    }else{
        let errores = document.querySelector(`[name="${input.name}"]`)
        errores.nextElementSibling.classList.remove("invisible");
        errores.nextElementSibling.classList.add("errores"); 
        input.style.border = "2px solid red";
        campos[input.name] = false;

        setTimeout(() => {
            errores.nextElementSibling.classList.remove("errores");
            errores.nextElementSibling.classList.add("invisible");
        }, 5000);
    }
    
}



const valContrasena = () => {
    const password1 = document.getElementById('password');
    const password2 = document.getElementById('passVerify');

    if (password1.value.length < 1) {

        password2.nextElementSibling.classList.remove("invisible");
        password2.nextElementSibling.classList.add("errores");
        password2.style.border = "2px solid red";
        campos["verify"] = false;

        setTimeout(() => {
            password2.nextElementSibling.classList.remove("errores");
            password2.nextElementSibling.classList.add("invisible");
        }, 5000);
        

    } else if(password1.value == password2.value){

        password2.nextElementSibling.classList.remove("errores")
        password2.nextElementSibling.classList.add("invisible");
        password2.style.border = "2px solid blue";
        campos["verify"] = true;
    }else {
        password2.nextElementSibling.classList.remove("invisible");
        password2.nextElementSibling.classList.add("errores");
        password2.style.border = "2px solid red";
        campos["verify"] = false;

        setTimeout(() => {
            password2.nextElementSibling.classList.remove("errores");
            password2.nextElementSibling.classList.add("invisible");
        }, 5000);
    }

   
};

const formBoton = () =>{


    if(campos.nombreCompleto && campos.documento && campos.correo && campos.contrasena && campos.verify){
        const boton = document.getElementById("Button_registro");
        boton.style.pointerEvents = "auto";
        boton.style.opacity = '1';
        
    }else{
        
        const boton = document.getElementById("Button_registro");
        boton.style.pointerEvents = "none";
        boton.style.opacity = '0.75';
    }

}

inputs.forEach(input=>{
    input.addEventListener("keyup", validacion);
    input.addEventListener('blur', validacion);
});












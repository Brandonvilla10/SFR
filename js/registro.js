// Obtiene el formulario por su ID
const formulario = document.getElementById("formulario");

// Este código evita que el formulario se envíe al presionar Enter
document.getElementById('formulario').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Evita el envío del formulario
    }
});

// Obtiene todos los inputs dentro del formulario
const inputs = document.querySelectorAll("#formulario input")

// Expresiones regulares para validar los campos
const expresiones = {
    documento: /^\d{6,10}$/, // Solo números de 6 a 10 dígitos
    nombre: /^[a-zA-ZÀ-ÿ\s]{4,40}$/, // Letras con o sin acentos y espacios, de 4 a 40 caracteres
    password: /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@#$!%?&]{6,100}$/, // Al menos una mayúscula y un número
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/, // Formato de correo electrónico
}

// Objeto para llevar el estado de validación de cada campo
const campos = {
    nombreCompleto: false,
    contrasena: false,
    correo: false,
    verify: false
}

// Función que se ejecuta cada vez que un input pierde el foco
const validacion = (e)  => {
    switch(e.target.name){
        case "nombreCompleto":
            validacionCampos(expresiones.nombre, e.target)
            formBoton();
            break;

        case "documento":
            validacionCampos(expresiones.documento, e.target)
            formBoton();
            break;
        
        case "correo":
            validacionCampos(expresiones.correo, e.target)
            formBoton();
            break;
        
        case "contrasena":
            validacionCampos(expresiones.password, e.target)
            formBoton();
            valContrasena();
            break;
        
        case "verify":
            valContrasena();
            formBoton();
            break;
    }
}

// Función que valida un campo individual
const validacionCampos = (expresion, input) => {
    if(expresion.test(input.value)){
        // Si es válido, ocultar el mensaje de error y marcar como válido
        let errores = document.querySelector(`[name="${input.name}"]`)
        errores.nextElementSibling.classList.remove("errores");
        errores.nextElementSibling.classList.add("invisible");
        input.style.border = "1px solid blue";
        input.style.backgroundColor = '#E8F0FE';
        campos[input.name] = true;
    } else {
        // Si no es válido, mostrar el mensaje de error
        let errores = document.querySelector(`[name="${input.name}"]`)
        errores.nextElementSibling.classList.remove("invisible");
        errores.nextElementSibling.classList.add("errores"); 
        input.style.border = "1px solid red";
        input.style.backgroundColor = '#D6D5D4';
        campos[input.name] = false;

        // Ocultar error después de 5 segundos
        setTimeout(() => {
            errores.nextElementSibling.classList.remove("errores");
            errores.nextElementSibling.classList.add("invisible");
        }, 5000);
    }
}

// Función que valida que las contraseñas coincidan
const valContrasena = () => {
    const password1 = document.getElementById('password');
    const password2 = document.getElementById('passVerify');

    if (password1.value.length < 1) {
        // Si el primer campo está vacío, marcar como inválido
        password2.nextElementSibling.classList.remove("invisible");
        password2.nextElementSibling.classList.add("errores");
        password2.style.border = "1px solid red";
        password2.style.backgroundColor = '#D6D5D4';
        campos["verify"] = false;

        setTimeout(() => {
            password2.nextElementSibling.classList.remove("errores");
            password2.nextElementSibling.classList.add("invisible");
        }, 5000);
    } else if(password1.value == password2.value){
        // Si las contraseñas coinciden
        password2.nextElementSibling.classList.remove("errores")
        password2.nextElementSibling.classList.add("invisible");
        password2.style.border = "1px solid blue";
        password2.style.backgroundColor = '#E8F0FE';
        campos["verify"] = true;
    } else {
        // Si no coinciden
        password2.nextElementSibling.classList.remove("invisible");
        password2.nextElementSibling.classList.add("errores");
        password2.style.border = "1px solid red";
        password2.style.backgroundColor = '#D6D5D4';
        campos["verify"] = false;

        setTimeout(() => {
            password2.nextElementSibling.classList.remove("errores");
            password2.nextElementSibling.classList.add("invisible");
        }, 5000);
    }
}

// Función que habilita o deshabilita el botón de registro según los campos válidos
const formBoton = () =>{
    if(campos.nombreCompleto && campos.documento && campos.correo && campos.contrasena && campos.verify){
        const boton = document.getElementById("Button_registro");
        boton.style.pointerEvents = "auto";
        boton.style.opacity = '1';
        boton.style.scale = '1.05';
    } else {
        const boton = document.getElementById("Button_registro");
        boton.style.pointerEvents = "none";
        boton.style.opacity = '0.60';
        boton.style.scale = '1';
    }
}

// Asigna el evento blur (cuando se sale del input) a todos los campos
inputs.forEach(input => {
    input.addEventListener('blur', validacion);
});

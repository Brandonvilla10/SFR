<div class="modalEliminar" id="modalEliminar">
    <div class="contenedorModalEliminar">

        <div class="headerModalEliminar">
            <h3>Verificación de Administrador</h3>
            <button class="cerrarModalEliminar" id="cerrarModalEliminar">&times; </button>
        </div> <!-- fin del heeader para eliminar usuario -->

        <div class="cuerpoModalEliminar">

            <form id="formEliminarUsuario" method="post"> <!-- aqui inicia el formulario pues para hacer todo el proceso -->

                <div class="campo-formulario">
                    <label for="documentoUsuario" id="tituloInput"></label>
                    <input type="text" id="inputIdentificador" name="identificador" placeholder="Digite Aqui" required>
                </div>

                <div class="campo-formulario">
                    <label for="passwordAdmin">Contraseña de Administrador.</label>
                    <div class="password-container">
                        <input type="password" id="passwordAdmin" name="passwordAdmin" placeholder="Ingrese su contraseña" required>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="botonesModalEliminar">
                    <button type="button" class="cancelar-btn" id="cancelarEliminar">Cancelar</button>
                    <button type="submit" name="eliminarSubmir" id="textoEliminar" class="eliminar-btn"></button>
                </div>
            </form>

        </div>
    </div>
</div>


<script>
    const modalEliminar = document.getElementById('modalEliminar');
    const botonEliminar = document.getElementById('botonEliminar');
    const cerrarModalEliminar = document.getElementById('cerrarModalEliminar');
    const cancelarEliminar = document.getElementById('cancelarEliminar');

    const passwordAdmin = document.getElementById('passwordAdmin');
    const togglePassword = document.getElementById('togglePassword');


    // variables para el abrir

    let tituloInput = document.getElementById('tituloInput');
    let inputIdentificador = document.getElementById('inputIdentificador');
    let textoEliminar =document.getElementById("textoEliminar")

    togglePassword.addEventListener('click', () => {
        if (passwordAdmin.type === 'password') {
            passwordAdmin.type = "text"
            togglePassword.innerHTML = '<i class="bi bi-eye-slash"></i></i>';
        } else {
            passwordAdmin.type = "password"
            togglePassword.innerHTML = ' <i class="bi bi-eye"></i>';

        }
    })



    function cerrar() {
        modalEliminar.style.visibility = 'hidden';
        modalEliminar.style.opacity = '0';
    }

    function abrir(tituloInputParametro,tipoDatoInput , inputIdentificadorParametro, buttonEliminar) {
        
        tituloInput.innerHTML = tituloInputParametro;
        
        if(tipoDatoInput == 1){
            inputIdentificador.type = "text";
        }else{
            inputIdentificador.type = "number";
        }
        inputIdentificador.placeholder = inputIdentificadorParametro;
        textoEliminar.innerHTML = buttonEliminar


        modalEliminar.style.visibility = 'visible';
        modalEliminar.style.opacity = '1';
    }

    cerrarModalEliminar.addEventListener('click', cerrar)
    cancelarEliminar.addEventListener('click', cerrar)
</script>
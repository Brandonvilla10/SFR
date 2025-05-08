<!-- modal.php -->
<div id="modalAlerta" class="modal-global" style="display: none">
    <h2 id="tituloModal">Success!</h2>
    <div class="line"></div>
    <p class="contenidoModal" id="contenidoModal">Haz sido agendado con éxito</p>
    <div class="botonesModal">
        <button class="button fill" onclick="cerrarModal()" id="success">Continuar</button>
    </div>
</div>

<div id="modalError" class="modal-global-error" style="display: none;">
    <h2 id="tituloModalError">ERROR!</h2>
    <div class="line"></div>
    <p class="contenidoModal" id="contenidoModalError">Error en el sistema, inténtalo de nuevo</p>
    <div class="botonesModal">
        <button class="button fill-error" onclick="cerrarModal()" id="mistake">Confirmar</button>
    </div>
</div>

<script>
    function mostrarModal(tipo = 'success', mensaje = '', ruta = '') {
        if (tipo === 'success') {
            document.getElementById('modalAlerta').style.display = 'block';
            document.getElementById('contenidoModal').innerText = mensaje;

            if (ruta != '') {
                document.getElementById('success').onclick = function() {
                    window.location.href = ruta;
                };
            }


        } else {
            document.getElementById('modalError').style.display = 'block';
            document.getElementById('contenidoModalError').innerText = mensaje;
            if (ruta != '') {
                document.getElementById('mistake').onclick = function() {
                    window.location.href = ruta;
                };
            }
        }
    }

    function cerrarModal() {
        document.getElementById('modalAlerta').style.display = 'none';
        document.getElementById('modalError').style.display = 'none';
    }
</script>

<style>
    body {
        font-family: 'Lato', sans-serif;
    }

    .modal-global,
    .modal-global-error {
        color: #34c768;
        text-align: center;
        position: absolute;
        inset: 0;
        margin: auto;
        width: 100%;
        max-width: 320px;
        height: 50%;
        max-height: fit-content;
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        overflow: visible;
        padding: 2rem;
        padding-top: 3rem;
        font-size: 23px;
        animation: entradaModal 0.6s ease-out;
        z-index: 1000;
    }

    .modal-global-error:hover,
    .modal-global:hover {
        transform: scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }

    .modal-global-error {
        color: #FF0000;
    }



    .modal-global::before,
    .modal-global-error::before {
        content: '';
        width: 100%;
        height: 25px;
        background-color: #34c759;
        position: absolute;
        top: 0;
        right: 0;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;

    }

    .modal-global-error::before {
        background-color: red;
    }

    .modal-global::after {
        content: '✓';
        font-size: 2rem;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        height: 70px;
        width: 70px;
        border-radius: 50%;
        background-color: #34c759;
        top: -25px;
        right: 30%;
        transform: translateX(-50%);
        background-color: #34c768;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);


    }

    .modal-global-error::after {
        content: 'X';
        font-size: 2rem;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        height: 70px;
        width: 70px;
        border-radius: 50%;
        background-color: #FF0000;
        top: -25px;
        right: 30%;
        transform: translateX(-50%);
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .line {
        width: 50px;
        height: 2px;
        background: #ccc;
        margin: -10px auto 20px;

    }

    .contenidoModal {
        color: #333;
        font-size: 16px;
        margin-bottom: 25px;
    }

    .button {
        padding: 0.3rem 1.5rem;
        margin-inline-end: 5px;
        border-radius: 20px;
        border: 2px solid;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .button:hover {
        scale: 1.1;
    }


    .no-fill {
        background: white;
        color: #34c759;
    }

    .fill {
        background: #34c759;
        color: white;
    }

    .error {
        color: red;
        border-color: white;
    }

    .error-fill {
        color: white;
        border-color: white;
        background-color: red;
    }

    @keyframes entradaModal {
        0% {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
</style>
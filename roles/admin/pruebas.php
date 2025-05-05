<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">

</head>

<body>

    <div class="modal-global">

        <h2>Success!</h2>
        <div class="line"></div>

        <p class="contenidoModal">Haz sido agendado con exito</p>

        <div class="botonesModal">

            <button class="button no-fill">Cancelar</button>
            <button class="button fill">Confirmar</button>

        </div>
    </div>
    <!-- 
     <div class="modal-global-error">

        <h2>ERROR!</h2>
        <div class="line"></div>    

        <p class="contenidoModal">Error en el sistema intentalo de nuevo</p>

        <div class="botonesModal">

            <button class="button error">Cancelar</button>
            <button class="button error-fill">Confirmar</button>

        </div>
    </div> -->



    <style>
        body {
            background-color: #CACDC6;
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
            border: 2px solid #34c759;
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
</body>

</html>
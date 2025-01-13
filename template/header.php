<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon"  href="../assets/img/LogoFR.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<header>

    <div class="Logo-Titulo">

        <img src="assets/img/LogoFR.png"  alt="Aqui va el logo SFR" width="60px" height="80px">
    
        <div class="SENA_FAST_REGISTER">
            <p>FAST REGISTER</p>
        </div>

    </div>

    <button class="hamburguesa_button"  id="abrir"><i class="bi bi-list"></i></button>

    <nav class="menu-header nav-hidden" id="nav">

        <ul class="listaheader">
            <button class="cerrar_button" id="cerrar"><i class="bi bi-x-lg"></i></button>
            <li class="listaMenu"><a href="index.php"><b>Inicio</b></a></li>
            <li class="listaespacio"><a href="#"><b>|</b></a></li>
            <li class="listaMenu"><a href="sobreNosotros.php"><b>Sobre Nosotros</b></a></li>
            <li class="listaespacio"><a href="#"><b>|</b></a></li>
            <li class="listaMenu"><a href="contactanos.php"><b>Contactanos</b></a></li>
            <li class="listaespacio"><a href="#"><b>|</b></a></li>
            
        </ul>
        
    </nav>   
</header>

    <div class="sombraNav"></div>

    <style>
    * {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
        color: rgba(58, 170, 53, 255);
    }

    header {
        align-items: center;
        display: flex;
        height: 100px;
        margin-bottom: 0;
        justify-content: space-between;
        box-sizing: border-box;
        padding: 0px  60px 0px 60px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        width: 100%;
    }

    .Logo-Titulo{
        display: flex;
        align-items: center;
        gap: 10px;
    }

    nav{
        opacity: 1;
        visibility: visible;
    }

    .listaMenu{
        color: rgba(58, 170, 53, 255);
    }
    .menu-header{
        opacity: 1;
        visibility: visible;
    }

    .listaheader{
        display: flex;
        list-style: none;
        list-style-type: none;
        gap: 40px;
    }

    .hamburguesa_button{
   
        display: none;
        
    }

    .cerrar_button{
        display: none;
        width: auto;
    }

    .SENA_FAST_REGISTER {
        width: 212px;
        height: 40px;
        font-weight: 900;
        font-size: x-large;
        color: rgba(58, 170, 53, 255);
    }

   
    
    @media  screen and (max-width:839px) {

        .hamburguesa_button{
            display: block;
            border: 0;
            font-size: 2rem;
            background-color: transparent;
            cursor: pointer;
        }

        .menu-header{
            opacity: 0;
            visibility: hidden;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            background-color: beige;
            padding: 15px;
            box-shadow: 0 0 0 100vmax rgba(0, 0, 0, .5); 
        }

        .nav-visible{
            opacity: 1;
            visibility: visible;
        }

        .nav-hidden{
            opacity: 0;
            visibility: hidden;
        }
       

        .listaheader{
            flex-direction: column;
            text-align: end;
            align-items: end;
        }
        
        

        .listaespacio{
            display: none;
        }

        .cerrar_button{
            display: block;
            border: 0;
            font-size: 1.25rem;
            background-color: transparent;
            cursor: pointer;
        }

    }

    @media  screen and (max-width:376px) {

        header{
            padding: 0px  5px 0px 10px;

        }

    }

    </style>

    <script>
        
        const nav = document.querySelector("#nav");
        const abrir = document.querySelector("#abrir");
        const cerrar = document.querySelector("#cerrar");


        
        abrir.addEventListener("click", () => {
            
            nav.classList.add('nav-visible');
            nav.classList.remove('nav-hidden'); 
        });

        cerrar.addEventListener("click", () => {

            nav.classList.add('nav-hidden');
            nav.classList.remove('nav-visible'); 
        });

        
        


    </script>
   

</body>
</html>
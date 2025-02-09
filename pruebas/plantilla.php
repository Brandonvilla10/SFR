<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMINISTRADOR</title>
</head>
<body>
<div class="container_grid">

    <aside class="bar">
        <?php include('asideBar.html')?>
    </aside>
    <nav class ="menu">
<<<<<<< HEAD
            <div class="imagenes"><a href="../../index.php"> <img src="../roles/admin/imgAdmin/ex.png" alt=" imagen de salida" width="38px" height= "38px"></a></div>
=======
            <div class="imagenes"><a href="../index.php"> <img src="../roles/admin/imgAdmin/ex.png" alt=" imagen de salida" width="38px" height= "38px"></a></div>
>>>>>>> 5c6b80ef449c8c77edac47147cc5ab48011fa1f3
            <div class="imagenes"> <img src="../roles/admin/imgAdmin/confi.png" alt="imagen de configuracion" width="38px" height= "38px"></div>
            <div class="imagenes"> <img src="../roles/admin/imgAdmin/notf.png" alt="imagen de notificaciones" width="38px" height= "38px"></div>
            <div class="imagenes"> <img src="../roles/admin/imgAdmin/usu.png" alt="imagen del usuario" width="38px" height= "38px"></div>
            <div>Brandon  ADMIN</div>
    </nav>

    <main class="contenido">
          
            
    </main>

</div>

<style>

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        transition: 0.5s;
    }

    .container_grid{
        display: grid;
        grid-template: 
            "sidebar menu" 100px
            "sidebar main" auto 
            /
            130px auto;
        width: 100vw;
        height: 100vh;
    }
    
    .menu{
        grid-area: menu;
        display: flex;
        flex-direction: row;
        align-items: end;
        justify-content: end;
        column-gap: 20px;
        padding-right: 50px;
    }
    .contenido{
        grid-area: main;
        justify-self: center;
        align-self: end;
       
       
    }
    .bar{
        grid-area: sidebar;
       
    }

    @media (min-width:320px), (max-width:320px){

        .container_grid{
        display: grid;
        grid-template: 
            "menu" 90px
            "main" auto 
            "sidebar" 80px
            /auto;
        width: 100vw;
        height: 100vh;
        }

        .menu{
            justify-self: center;
            align-self: center;
            padding-left: 20px;
            padding-right: 0;
            column-gap: 10%;
        }
        .menu .imagenes img{
            width: 30px;
            height: 30px;
        }
    }

    @media (min-width:425px){
        .menu{
            gap: 8%;
            padding-left: 20px;
            padding-right: 10px;
            width: 100%;
            justify-content: space-evenly;
            align-items: center;
        }
        .menu .imagenes img{
            
            width: 38px;
            height: 38px; 
        }
    }

    @media (min-width: 767px) {
        .container_grid{
        display: grid;
        grid-template: 
            "sidebar menu" 100px
            "sidebar main" auto 
            /
            120px auto;
        width: 100vw;
        height: 100vh;
        
        }

                    /* aqui termine de revisar  */
        .menu{
            grid-area: menu;
            justify-self: end;
            width: auto;
            column-gap: 10%;
            justify-self: end;
            align-items: center;
            padding-right: 50px;
            gap: 25px;
        }

        .contenido{
            grid-area: main;
            width: 100%;
            height: 100%;
           
        }

        .bar{
            grid-area: sidebar;
        }
    }
    
    

    
    

    @media (min-width:992px){

        .container_grid{
            grid-template: 
            "sidebar menu" 100px
            "sidebar main" auto 
            /
            150px auto;
        }
        .menu{
            justify-self: end;
        }
       

    }

   
    
  

    @media (min-width:1800px){
        .container_grid{
        display: grid;
        grid-template: 
            "sidebar menu" 100px
            "sidebar main" auto 
            /
            160px auto;
        width: 100vw;
        height: 100vh;
        }
        .menu .imagenes img{
            width: 40px;
            height: 40px;
        }
    }

    @media (min-width:2400px){
        .container_grid{
            display: grid;
            grid-template: 
                "sidebar menu" 100px
                "sidebar main" auto 
                /
                180px auto; 
        }

        .menu .imagenes img{
            width: 50px;
            height: 50px;
        }
    }

    


</style>

</body>
</html>
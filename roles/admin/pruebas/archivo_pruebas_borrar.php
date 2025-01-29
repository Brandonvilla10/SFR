<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartas Pokémon</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .contenedor-cartas {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 10px;
        }

        .carta {
            width: 120px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 10px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .carta:hover {
            transform: scale(1.1);
        }

        .carta img {
            width: 100%;
            border-radius: 8px;
        }

        .nombre {
            font-weight: bold;
            margin: 5px 0;
        }

        .seleccionar {
            background-color: #ffcc00;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .seleccionar:hover {
            background-color: #e6b800;
        }
    </style>
</head>
<body>

    <h2>Escoge tu Pokémon</h2>

    <div class="contenedor-cartas" id="cartas-container">
        <!-- Cartas se agregarán dinámicamente -->
    </div>

    <script>
        const pokemones = [
            { nombre: "Pikachu", imagen: "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/25.png" },
            { nombre: "Charmander", imagen: "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/4.png" },
            { nombre: "Bulbasaur", imagen: "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/1.png" },
            { nombre: "Squirtle", imagen: "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/7.png" }
        ];

        const contenedor = document.getElementById("cartas-container");

        pokemones.forEach(pokemon => {
            const carta = document.createElement("div");
            carta.classList.add("carta");

            const imagen = document.createElement("img");
            imagen.src = pokemon.imagen;

            const nombre = document.createElement("p");
            nombre.classList.add("nombre");
            nombre.textContent = pokemon.nombre;

            const boton = document.createElement("button");
            boton.classList.add("seleccionar");
            boton.textContent = "Seleccionar";
            boton.addEventListener("click", () => {
                alert(`¡Has seleccionado a ${pokemon.nombre}!`);
            });

            carta.appendChild(imagen);
            carta.appendChild(nombre);
            carta.appendChild(boton);
            contenedor.appendChild(carta);
        });
    </script>

</body>
</html>

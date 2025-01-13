<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Errores estilo `required`</title>
  <style>
    /* Estilo básico para el campo */
    .input-error {
      border: 1px solid red;
    }

    /* Tooltip personalizado */
    .tooltip {
      position: absolute;
      background-color: #f44336; /* Rojo */
      color: white;
      padding: 5px;
      border-radius: 5px;
      font-size: 12px;
      white-space: nowrap;
  
      transform: translate(15%,-100%);
      visibility: hidden;
      opacity: 0;
      transition: opacity 0.2s;
    }

    .input-container {
      position: relative; /* Necesario para el tooltip */
    }

    .input-container .tooltip.active {
      visibility: visible;
      opacity: 1;
    }
  </style>
</head>
<body>
  <form id="myForm" novalidate>
    <div class="input-container">
      <label for="name">Nombre:</label>
      <input type="text" id="name" name="name" placeholder="Escribe tu nombre">
      <div class="tooltip" id="tooltip-name">Este campo es obligatorio.</div>
    </div>
    <br>
    <div class="input-container">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Escribe tu correo">
      <div class="tooltip" id="tooltip-email">Introduce un correo válido.</div>
    </div>
    <br>
    <button type="submit">Enviar</button>
  </form>

  <script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Evita el envío por defecto del formulario

      // Limpiar estados previos
      const tooltips = document.querySelectorAll('.tooltip');
      const inputs = document.querySelectorAll('input');
      tooltips.forEach(tooltip => tooltip.classList.remove('active'));
      inputs.forEach(input => input.classList.remove('input-error'));

      // Validar los campos
      let isValid = true;

      const nameInput = document.getElementById('name');
      const nameTooltip = document.getElementById('tooltip-name');
      if (!nameInput.value.trim()) {
        nameInput.classList.add('input-error');
        nameTooltip.classList.add('active');
        isValid = false;
      }

      const emailInput = document.getElementById('email');
      const emailTooltip = document.getElementById('tooltip-email');
      if (!emailInput.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        emailInput.classList.add('input-error');
        emailTooltip.classList.add('active');
        isValid = false;
      }

      if (isValid) {
        alert('Formulario enviado correctamente.');
        // Aquí puedes enviar el formulario o realizar otra acción
      }
    });
  </script>
</body>
</html>
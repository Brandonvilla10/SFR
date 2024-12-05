

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener("input", function(event) {
        event.preventDefault();

        const documento = document.querySelector('input[name="documento"]').value;
        const contraseña = document.querySelector('input[name="contraseña"]').value;

        let errors = [];

        // Validar Documento
        if (documento === '') {
            errors.push('El campo Documento es obligatorio.');
            document.querySelector('#documento-error').textContent = 'El campo Documento es obligatorio.';
        } else if (isNaN(documento) || documento.length < 6) {
            errors.push('El Documento debe ser un número y tener al menos 6 dígitos.');
            document.querySelector('#documento-error').textContent = 'El Documento debe ser un número y tener al menos 6 dígitos.';
        } else {
            document.querySelector('#documento-error').textContent = ''; // Limpiar error si válido
        }

        // Validar Contraseña
        if (contraseña === '') {
            errors.push('El campo Contraseña es obligatorio.');
            document.querySelector('#contraseña-error').textContent = 'El campo Contraseña es obligatorio.';
        } else if (contraseña.length < 8) {
            errors.push('La Contraseña debe tener al menos 8 caracteres.');
            document.querySelector('#contraseña-error').textContent = 'La Contraseña debe tener al menos 8 caracteres.';
        } else {
            document.querySelector('#contraseña-error').textContent = ''; // Limpiar error si válido
        }

        // Si hay errores, mostrar
        if (errors.length > 0) {
            // Si no hay errores, puedes realizar el submit o manejar el envío del formulario
            return false;
        }
    });
});


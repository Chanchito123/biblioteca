document.addEventListener("DOMContentLoaded", function() {
    // Validación de formularios
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            let valid = true;

            // Obtener campos del formulario
            const email = form.querySelector("input[type='email']");
            const password = form.querySelector("input[type='password']");
            const nombre = form.querySelector("input[name='nombre']");
            const carrera = form.querySelector("input[name='carrera']");
            const matricula = form.querySelector("input[name='matricula']");

            // Validar campos requeridos
            if (!nombre.value || !carrera.value || !matricula.value || !email.value || !password.value) {
                valid = false;
                alert("Por favor, completa todos los campos requeridos.");
            }

            // Validar formato del email
            const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
            if (email.value && !emailPattern.test(email.value)) {
                valid = false;
                alert("Por favor, ingresa un email válido.");
            }

            if (!valid) {
                event.preventDefault(); // Evitar el envío del formulario si hay errores
            }
        });

        // Confirmaciones para acciones críticas
        if (form.querySelector("button[name='prestamo']")) {
            form.addEventListener("submit", function(event) {
                const confirmAction = confirm("¿Estás seguro de que deseas prestar este libro?");
                if (!confirmAction) {
                    event.preventDefault(); // Evitar el envío del formulario si el usuario cancela
                } else {
                    decreaseBookQuantity(form); // Disminuir cantidad al prestar
                }
            });
        }

        if (form.querySelector("button[name='devolucion']")) {
            form.addEventListener("submit", function(event) {
                const confirmAction = confirm("¿Estás seguro de que deseas devolver este libro?");
                if (!confirmAction) {
                    event.preventDefault(); // Evitar el envío del formulario si el usuario cancela
                } else {
                    increaseBookQuantity(form); // Aumentar cantidad al devolver
                }
            });
        }
    });

    // Función para disminuir la cantidad de libros prestados
    function decreaseBookQuantity(form) {
        const bookId = form.querySelector("input[name='bookId']").value; // Suponiendo que tienes un campo oculto con el ID del libro
        let quantityElement = document.getElementById(`quantity-${bookId}`); // Elemento que muestra la cantidad disponible

        if (quantityElement) {
            let currentQuantity = parseInt(quantityElement.textContent);
            if (currentQuantity > 0) {
                currentQuantity -= 1;
                quantityElement.textContent = currentQuantity; // Actualizar la cantidad en la interfaz
                showMessage(`Se ha prestado un libro. Cantidad restante: ${currentQuantity}`, false);
            } else {
                alert("No hay suficientes libros disponibles para prestar.");
            }
        }
    }

    // Función para aumentar la cantidad de libros devueltos
    function increaseBookQuantity(form) {
        const bookId = form.querySelector("input[name='bookId']").value; // Suponiendo que tienes un campo oculto con el ID del libro
        let quantityElement = document.getElementById(`quantity-${bookId}`); // Elemento que muestra la cantidad disponible

        if (quantityElement) {
            let currentQuantity = parseInt(quantityElement.textContent);
            currentQuantity += 1;
            quantityElement.textContent = currentQuantity; // Actualizar la cantidad en la interfaz
            showMessage(`Se ha devuelto un libro. Cantidad total: ${currentQuantity}`, false);
        }
    }

    // Manejo de mensajes (opcional)
    window.showMessage = function(message, isError) {
        const messageBox = document.getElementById('messageBox'); // Asegúrate de tener un contenedor para mensajes
        messageBox.textContent = message;
        messageBox.style.color = isError ? 'red' : 'green';
        messageBox.style.display = 'block';
    };
});
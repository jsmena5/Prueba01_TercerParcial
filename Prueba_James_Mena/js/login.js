
    // JavaScript para manejar los eventos
    const registroLink = document.getElementById('link-registro');
    const productoLink = document.getElementById('link-producto');

    // Cambiar color al pasar el cursor sobre "Formulario Registro"
    registroLink.addEventListener('mouseover', function() {
        if (!registroLink.classList.contains('clicked')) {
            registroLink.style.color = 'darkblue';
        }
    });

    // Volver al color original al salir del cursor, si no ha sido clickeado
    registroLink.addEventListener('mouseout', function() {
        if (!registroLink.classList.contains('clicked')) {
            registroLink.style.color = 'purple';
        }
    });

    // Cambiar color permanentemente después de dar clic en "Formulario Registro"
    registroLink.addEventListener('click', function() {
        registroLink.style.color = 'darkblue';
        registroLink.classList.add('clicked');
    });

    // Cambiar color al pasar el cursor sobre "Formulario Producto"
    productoLink.addEventListener('mouseover', function() {
        if (!productoLink.classList.contains('clicked')) {
            productoLink.style.color = 'purple';
        }
    });

    // Volver al color original al salir del cursor, si no ha sido clickeado
    productoLink.addEventListener('mouseout', function() {
        if (!productoLink.classList.contains('clicked')) {
            productoLink.style.color = 'darkblue';
        }
    });

    // Cambiar color permanentemente después de dar clic en "Formulario Producto"
    productoLink.addEventListener('click', function() {
        productoLink.style.color = 'purple';
        productoLink.classList.add('clicked');
    });




document.getElementById('btn-login').addEventListener('click', function (event) {
    event.preventDefault(); // Prevenir comportamiento predeterminado
    window.location.href = 'Formulario_Login.html'; // Redirigir al formulario de login
});

   

/* =====================================
   GAMEZONE - JAVASCRIPT
===================================== */

document.addEventListener("DOMContentLoaded", function () {

    console.log("GameZone: JavaScript funcionando correctamente.");

    /* =====================================
       1. BUSCADOR DE VIDEOJUEGOS
    ===================================== */

    const busqueda = document.getElementById("busqueda");

    const filtroGenero = document.getElementById("filtroGenero");

    const tarjetas = document.querySelectorAll(".juego");

    const sinResultados = document.getElementById("sinResultados");

    function filtrarVideojuegos() {

        if (!busqueda || !filtroGenero) {
            return;
        }

        const texto = busqueda.value.toLowerCase().trim();

        const generoSeleccionado = filtroGenero.value.toLowerCase();

        let encontrados = 0;

        tarjetas.forEach(function (tarjeta) {

            const nombre = tarjeta.dataset.nombre.toLowerCase();

            // Separar los géneros del videojuego
            const generos = tarjeta.dataset.genero
                .split(",")
                .map(function (genero) {
                    return genero.trim().toLowerCase();
                });

            const coincideNombre = nombre.includes(texto);

            // Comprobar si coincide alguno de los géneros
            const coincideGenero =
                generoSeleccionado === "" ||
                generos.includes(generoSeleccionado);

            const mostrar = coincideNombre && coincideGenero;

            tarjeta.classList.toggle("d-none", !mostrar);

            if (mostrar) {
                encontrados++;
            }

        });

        if (sinResultados) {

            sinResultados.classList.toggle(
                "d-none",
                encontrados > 0
            );

        }

    }

    if (busqueda) {
        busqueda.addEventListener("input", filtrarVideojuegos);
    }

    if (filtroGenero) {
        filtroGenero.addEventListener("change", filtrarVideojuegos);
    }


    /* =====================================
       2. VALIDACIÓN DE FORMULARIOS
    ===================================== */

    const formulario = document.getElementById("formularioJuego");

    if (formulario) {

        formulario.addEventListener("submit", function (evento) {

            const nombre = formulario.querySelector(
                '[name="nombre"]'
            );

            const precio = formulario.querySelector(
                '[name="precio"]'
            );

            const anio = formulario.querySelector(
                '[name="anio"]'
            );

            if (nombre && nombre.value.trim() === "") {

                evento.preventDefault();
                alert("Debes ingresar el nombre del videojuego.");
                nombre.focus();
                return;
            }

            if (precio && Number(precio.value) < 0) {

                evento.preventDefault();
                alert("El precio no puede ser negativo.");
                precio.focus();
                return;
            }

            if (anio && Number(anio.value) < 1950) {

                evento.preventDefault();
                alert("Ingresa un año válido.");
                anio.focus();
                return;
            }

        });

    }


    /* =====================================
       3. CONFIRMACIÓN DE ELIMINACIÓN
    ===================================== */

    const formulariosEliminar = document.querySelectorAll(
        'form[action="eliminar.php"]'
    );

    formulariosEliminar.forEach(function (formularioEliminar) {

        formularioEliminar.addEventListener("submit", function (evento) {

            const confirmar = confirm(
                "¿Estás seguro de que deseas eliminar este videojuego?"
            );

            if (!confirmar) {

                evento.preventDefault();

            }

        });

    });


    /* =====================================
       4. MENSAJE DE BIENVENIDA
    ===================================== */

    console.log("Todas las funciones de GameZone están listas.");

});

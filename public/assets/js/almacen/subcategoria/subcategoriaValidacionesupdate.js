document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("subcategoriaForm");
    const inputNombre = document.getElementById("nombre");
    const idSubcategoria = window.idSubcategoria ?? null;

    const mostrarError = (input, mensaje, clase = "error-msg") => {
        const parent = input.parentElement;
        const oldError = parent.querySelector(`.${clase}`);
        if (oldError) oldError.remove();

        const msg = document.createElement("p");
        msg.className = `text-red-500 text-sm mt-1 ${clase}`;
        msg.innerText = mensaje;
        parent.appendChild(msg);
        input.classList.add("border-red-500");
    };

    const limpiarErrores = (input) => {
        const parent = input.parentElement;
        input.classList.remove("border-red-500");
        input.removeAttribute("data-duplicado");
        const errores = parent.querySelectorAll(".error-msg, .error-msg-duplicado");
        errores.forEach(el => el.remove());
    };

    // Validación de duplicado
    const validarNombreDuplicado = () => {
        const nombre = inputNombre.value.trim();
        limpiarErrores(inputNombre);

        if (!nombre) {
            mostrarError(inputNombre, "El campo nombre es obligatorio.");
            return;
        }

        const url = `/api/validar-nombre-subcategoria?nombre=${encodeURIComponent(nombre)}${idSubcategoria ? `&id=${idSubcategoria}` : ''}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    inputNombre.setAttribute("data-duplicado", "true");
                    mostrarError(inputNombre, "El nombre ya existe en el sistema.", "error-msg-duplicado");
                }
            })
            .catch(error => {
                console.error("Error al validar duplicado:", error);
            });
    };

    // Validación en tiempo real
    inputNombre.addEventListener("input", () => {
        limpiarErrores(inputNombre);
        const nombre = inputNombre.value.trim();
        if (!nombre) {
            mostrarError(inputNombre, "El campo nombre es obligatorio.");
        }
    });

    inputNombre.addEventListener("blur", validarNombreDuplicado);

    // Validación al enviar
    form.addEventListener("submit", function (e) {
        let valid = true;
        limpiarErrores(inputNombre);

        const nombre = inputNombre.value.trim();
        if (!nombre) {
            valid = false;
            mostrarError(inputNombre, "El campo nombre es obligatorio.");
        } else if (inputNombre.getAttribute("data-duplicado") === "true") {
            valid = false;
            mostrarError(inputNombre, "El nombre ya existe en el sistema.", "error-msg-duplicado");
        }

        if (!valid) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
});

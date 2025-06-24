document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("kitForm");
    const submitBtn = document.getElementById("btnGuardar");

    // Campos a validar
    const campos = [
        { id: "codigo_barras", name: "Código de Barras" },
        { id: "sku", name: "SKU" },
        { id: "nombre", name: "Nombre del Kit" },
        { id: "precio_venta", name: "Precio de Venta" },
        { id: "stock_total", name: "Stock Total" },
        { id: "stock_minimo", name: "Stock Mínimo" }
    ];

    const camposUnicos = ["codigo_barras", "sku"];

    // Validación de campos únicos
    camposUnicos.forEach(campoId => {
        const input = document.getElementById(campoId);
        if (!input) return;

        const validarDuplicado = () => {
            const valor = input.value.trim();
            if (!valor) return;

            const url = `/api/validar-${campoId}-kit?valor=${encodeURIComponent(valor)}`;
            
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const parent = input.parentElement.classList.contains("flex")
                        ? input.parentElement.parentElement
                        : input.parentElement;

                    const oldError = parent.querySelector(".error-msg-duplicado");
                    if (oldError) oldError.remove();

                    input.classList.remove("border-red-500");
                    input.removeAttribute("data-duplicado");

                    if (data.exists) {
                        input.classList.add("border-red-500");
                        input.setAttribute("data-duplicado", "true");

                        const msg = document.createElement("p");
                        msg.className = "text-red-500 text-sm mt-1 error-msg-duplicado";
                        msg.innerText = `El ${campoId.replace('_', ' ')} ya existe en el sistema.`;
                        parent.appendChild(msg);
                    }
                })
                .catch(error => {
                    console.error("Error en la validación de", campoId, error);
                });
        };

        input.addEventListener("blur", validarDuplicado);

        input.addEventListener("input", function () {
            const parent = input.parentElement.classList.contains("flex")
                ? input.parentElement.parentElement
                : input.parentElement;

            const error = parent.querySelector(".error-msg-duplicado");
            if (error) error.remove();

            input.removeAttribute("data-duplicado");
            input.classList.remove("border-red-500");
        });
    });

    // Validación general de campos
    function validarCampos() {
        let todosValidos = true;

        // Validar campos obligatorios
        campos.forEach(campo => {
            const input = document.getElementById(campo.id);
            if (!input) return;

            const value = input.value.trim();
            let isInvalid = false;

            if (input.tagName === "SELECT") {
                isInvalid = input.selectedIndex === 0 || !value;
            } else {
                isInvalid = !value;
            }

            if (isInvalid) {
                todosValidos = false;
            }

            // Validaciones específicas
            if (campo.id === "stock_total" && value && parseInt(value) < 0) {
                todosValidos = false;
            }

            if (campo.id === "stock_minimo" && value && parseInt(value) <= 0) {
                todosValidos = false;
            }

            if (campo.id === "precio_venta" && value && parseFloat(value) <= 0) {
                todosValidos = false;
            }
        });

        // Validar que haya productos en el kit
        const productosKit = JSON.parse(document.getElementById("productos_kit").value || "[]");
        if (productosKit.length === 0) {
            todosValidos = false;
            const container = document.querySelector(".productos-seleccionados");
            const error = container.querySelector(".error-msg");
            if (!error) {
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = "Debe agregar al menos un producto al kit";
                container.appendChild(msg);
            }
        } else {
            const error = document.querySelector(".productos-seleccionados .error-msg");
            if (error) error.remove();
        }

        return todosValidos;
    }

    // Event listeners para validación en tiempo real
    campos.forEach(campo => {
        const input = document.getElementById(campo.id);
        if (!input) return;

        const limpiarErrorYValidar = () => {
            input.classList.remove("border-red-500");

            const parent = input.parentElement.classList.contains("flex")
                ? input.parentElement.parentElement
                : input.parentElement;

            const errorMsg = parent.querySelector(".error-msg");
            if (errorMsg) errorMsg.remove();

            validarCampos();
        };

        if (input.tagName === "SELECT") {
            input.addEventListener("change", limpiarErrorYValidar);
        } else {
            input.addEventListener("input", limpiarErrorYValidar);
        }
    });

    // Validación al enviar el formulario (solo validación, sin guardar)
    document.getElementById("btnGuardar").addEventListener("click", function (e) {
        e.preventDefault();
        
        let valid = true;
        document.querySelectorAll(".error-msg").forEach(el => el.remove());

        // Validar campos obligatorios
        campos.forEach(campo => {
            const input = document.getElementById(campo.id);
            if (!input) return;

            const value = input.value.trim();
            const parent = input.parentElement.classList.contains("flex")
                ? input.parentElement.parentElement
                : input.parentElement;

            let isInvalid = false;
            if (input.tagName === "SELECT") {
                isInvalid = input.selectedIndex === 0 || !value;
            } else {
                isInvalid = !value;
            }

            if (isInvalid) {
                valid = false;
                input.classList.add("border-red-500");

                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = `El campo ${campo.name} es obligatorio.`;
                parent.appendChild(msg);
            }

            // Validaciones específicas
            if (campo.id === "stock_total" && value && parseInt(value) < 0) {
                valid = false;
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = "El stock total no puede ser menor que 0.";
                parent.appendChild(msg);
            }

            if (campo.id === "stock_minimo" && value && parseInt(value) <= 0) {
                valid = false;
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = "El stock mínimo debe ser mayor que 0.";
                parent.appendChild(msg);
            }

            if (campo.id === "precio_venta" && value && parseFloat(value) <= 0) {
                valid = false;
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = "El precio de venta debe ser mayor que 0.";
                parent.appendChild(msg);
            }
        });

        // Validar campos únicos
        camposUnicos.forEach(campoId => {
            const input = document.getElementById(campoId);
            if (input && input.getAttribute("data-duplicado") === "true") {
                valid = false;
            }
        });

        // Validar productos del kit
        const productosKit = JSON.parse(document.getElementById("productos_kit").value || "[]");
        if (productosKit.length === 0) {
            valid = false;
            const container = document.querySelector(".productos-seleccionados");
            const msg = document.createElement("p");
            msg.className = "text-red-500 text-sm mt-1 error-msg";
            msg.innerText = "Debe agregar al menos un producto al kit";
            container.appendChild(msg);
        }

        if (valid) {
            // toastr.success("Todo válido, puedes proceder a guardar manualmente.");
        } else {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Validación inicial
    validarCampos();
});

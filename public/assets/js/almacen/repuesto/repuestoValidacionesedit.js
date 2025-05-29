document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("articuloForm");
    const submitBtn = form.querySelector('button[type="submit"]');

    const campos = [
        { id: "codigo_barras", name: "Código de Barras" },
        { id: "sku", name: "SKU" },
        { id: "codigo_repuesto", name: "Código Repuesto" },
        { id: "idModelo", name: "Modelo" },
        { id: "precio_compra", name: "Precio de Compra" },
        { id: "precio_venta", name: "Precio de Venta" },
        { id: "stock_total", name: "Stock Total" },
        { id: "stock_minimo", name: "Stock Mínimo" },
        { id: "idUnidad", name: "Unidad de Medida" },
        { id: "pulgadas", name: "Pulgadas" },
    ];

    const camposUnicos = ["codigo_barras", "sku", "codigo_repuesto"];

    camposUnicos.forEach(campoId => {
        const input = document.getElementById(campoId);
        if (!input) return;

        const validarDuplicado = () => {
            const valor = input.value.trim();
            if (!valor) return;

            fetch(`/api/validar-${campoId}?valor=${encodeURIComponent(valor)}`)
                .then(res => res.json())
                .then(data => {
                    const parent = input.parentElement.classList.contains("flex")
                        ? input.parentElement.parentElement
                        : input.parentElement;

                    // Solo removemos el duplicado anterior si existe
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

    function validarCampos() {
        let todosValidos = true;

        campos.forEach(campo => {
            const input = document.getElementById(campo.id);
            if (!input) return;

            const value = input.value.trim();
            const isInvalid = !value || (input.tagName === "SELECT" && input.selectedIndex === 0);

            if (campo.id !== 'stock_minimo' && isInvalid) {
                todosValidos = false;
            }

            if ((campo.id === "stock_total" || campo.id === "stock_minimo") && value) {
                const num = parseInt(value);
                if ((campo.id === "stock_total" && num < 0) || (campo.id === "stock_minimo" && num <= 0)) {
                    todosValidos = false;
                }
            }

            const precioCompra = parseFloat(document.getElementById("precio_compra").value);
            const precioVenta = parseFloat(document.getElementById("precio_venta").value);
            if (!isNaN(precioCompra) && precioCompra <= 0) {
                todosValidos = false;
            }
            if (!isNaN(precioVenta) && precioVenta <= 0) {
                todosValidos = false;
            }
            if (!isNaN(precioCompra) && !isNaN(precioVenta) && precioCompra > precioVenta) {
                todosValidos = false;
            }
        });

        submitBtn.classList.toggle('opacity-50', !todosValidos);
        submitBtn.classList.toggle('cursor-not-allowed', !todosValidos);
    }

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

    form.addEventListener("submit", function (e) {
        let valid = true;

        // Solo eliminamos errores generales, NO duplicados
        document.querySelectorAll(".error-msg").forEach(el => el.remove());

        campos.forEach(campo => {
            const input = document.getElementById(campo.id);
            if (!input) return;

            const value = input.value.trim();
            const parent = input.parentElement.classList.contains("flex")
                ? input.parentElement.parentElement
                : input.parentElement;

            const isInvalid = !value || (input.tagName === "SELECT" && input.selectedIndex === 0);

            if (campo.id !== 'stock_minimo' && isInvalid) {
                valid = false;
                input.classList.add("border-red-500");

                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = `El campo ${campo.name} es obligatorio.`;
                parent.appendChild(msg);
            }

          if (campo.id === "precio_venta" || campo.id === "precio_compra") {
                const pc = parseFloat(document.getElementById("precio_compra").value);
                const pv = parseFloat(document.getElementById("precio_venta").value);

                // Validación para el precio de compra
                if (!isNaN(pc) && pc <= 0) {
                    valid = false;
                    const msg = document.createElement("p");
                    msg.className = "text-red-500 text-sm mt-1 error-msg";
                    msg.innerText = `El precio de compra no puede ser menor o igual a 0.`;
                    parent.appendChild(msg);
                }

                // Solo se valida el precio de venta si el precio de compra es válido
                if (valid && !isNaN(pv) && pv <= 0) {
                    valid = false;
                    const msg = document.createElement("p");
                    msg.className = "text-red-500 text-sm mt-1 error-msg";
                    msg.innerText = `El precio de venta no puede ser menor o igual a 0.`;
                    parent.appendChild(msg);
                }

                // Validación para el precio de compra siendo mayor al precio de venta
                if (valid && !isNaN(pc) && !isNaN(pv) && pc > pv) {
                    valid = false;
                    const msg = document.createElement("p");
                    msg.className = "text-red-500 text-sm mt-1 error-msg";
                    msg.innerText = `El precio de compra no puede ser mayor que el precio de venta.`;
                    parent.appendChild(msg);
                }
            }

            if (campo.id === "stock_total" && value !== "" && parseInt(value) < 0) {
                valid = false;
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = `El stock total no puede ser menor que 0.`;
                parent.appendChild(msg);
            }

            if (campo.id === "stock_minimo" && value !== "" && parseInt(value) <= 0) {
                valid = false;
                const msg = document.createElement("p");
                msg.className = "text-red-500 text-sm mt-1 error-msg";
                msg.innerText = `El stock mínimo debe ser mayor que 0.`;
                parent.appendChild(msg);
            }
        });

        // Cancelar envío si hay algún campo con duplicado
        camposUnicos.forEach(campoId => {
            const input = document.getElementById(campoId);
            if (input && input.getAttribute("data-duplicado") === "true") {
                valid = false;
            }
        });

        if (!valid) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    validarCampos();
});


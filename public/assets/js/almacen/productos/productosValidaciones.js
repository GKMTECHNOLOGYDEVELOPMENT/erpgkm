document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("productoForm");
    const submitBtn = form.querySelector('button[type="submit"]');

    // 🟡 ID del artículo si estás editando
    const idArticulo = window.idArticulo ?? null;
    console.log("🟡 ID del artículo (modo edición):", idArticulo);

    const campos = [
        { id: "codigo_barras", name: "Código de Barras" },
        { id: "sku", name: "SKU" },
        { id: "codigo_repuesto", name: "Código Repuesto" },
        { id: "idModelo", name: "Modelo" },
        { id: "precio_compra", name: "Precio de Compra" },
        { id: "precio_venta", name: "Precio de Venta" },
        { id: "stock_total", name: "Stock Total" },
        { id: "idUnidad", name: "Unidad de Medida" },
        { id: "nombre", name: "Nombre" },
        { id: "peso", name: "Peso" },
        { id: "stock_minimo", name: "Stock Mínimo" },
        // Nuevos campos agregados
        { id: "garantia_fabrica", name: "Garantía de Fábrica", optional: true },
        { id: "unidad_tiempo_garantia", name: "Unidad de Tiempo Garantía", optional: true },
        { id: "idProveedor", name: "Proveedor", optional: true }
    ];

    const camposUnicos = ["codigo_barras", "sku", "codigo_repuesto"];

    // Función para validar campos de garantía
    function validarGarantia() {
        const garantiaInput = document.getElementById("garantia_fabrica");
        const unidadTiempoSelect = document.getElementById("unidad_tiempo_garantia");
        const garantiaValue = garantiaInput ? garantiaInput.value.trim() : "";
        
        // Si hay valor en garantía, la unidad de tiempo es obligatoria
        if (garantiaValue && garantiaValue > 0) {
            if (!unidadTiempoSelect || unidadTiempoSelect.value === "") {
                return false;
            }
        }
        
        return true;
    }

    // Mostrar/ocultar unidad de tiempo según garantía
    function toggleUnidadTiempo() {
        const garantiaInput = document.getElementById("garantia_fabrica");
        const unidadTiempoContainer = document.querySelector('[for="unidad_tiempo_garantia"]').parentElement;
        
        if (garantiaInput && garantiaInput.value > 0) {
            unidadTiempoContainer.style.display = "block";
        } else {
            unidadTiempoContainer.style.display = "none";
        }
    }

    // Inicializar visibilidad de unidad de tiempo
    toggleUnidadTiempo();
    
    // Event listener para cambios en garantía
    const garantiaInput = document.getElementById("garantia_fabrica");
    if (garantiaInput) {
        garantiaInput.addEventListener("input", function() {
            toggleUnidadTiempo();
            validarCampos();
        });
    }

    camposUnicos.forEach(campoId => {
        const input = document.getElementById(campoId);
        if (!input) return;

        const validarDuplicado = () => {
            const valor = input.value.trim();
            if (!valor) {
                console.log(`⚠️ ${campoId} vacío, no se valida`);
                return;
            }

            const url = `/api/validar-${campoId}?valor=${encodeURIComponent(valor)}${idArticulo ? `&id=${idArticulo}` : ''}`;
            console.log("🔍 Validando duplicado para:", campoId);
            console.log("📤 URL:", url);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    console.log("📥 Respuesta para", campoId, "=>", data);

                    const parent = input.parentElement.classList.contains("flex")
                        ? input.parentElement.parentElement
                        : input.parentElement;

                    const oldError = parent.querySelector(".error-msg-duplicado");
                    if (oldError) {
                        console.log("🧹 Eliminando error anterior para", campoId);
                        oldError.remove();
                    }

                    input.classList.remove("border-red-500");
                    input.removeAttribute("data-duplicado");

                    if (data.exists) {
                        console.log("❌ Duplicado encontrado en", campoId);
                        input.classList.add("border-red-500");
                        input.setAttribute("data-duplicado", "true");

                        const msg = document.createElement("p");
                        msg.className = "text-red-500 text-sm mt-1 error-msg-duplicado";
                        msg.innerText = `El ${campoId.replace('_', ' ')} ya existe en el sistema.`;
                        parent.appendChild(msg);
                    } else {
                        console.log("✅ No duplicado para", campoId);
                    }
                })
                .catch(error => {
                    console.error("❗ Error en la validación de", campoId, error);
                });
        };

        input.addEventListener("blur", validarDuplicado);

        input.addEventListener("input", function () {
            const parent = input.parentElement.classList.contains("flex")
                ? input.parentElement.parentElement
                : input.parentElement;

            const error = parent.querySelector(".error-msg-duplicado");
            if (error) {
                console.log("🧹 Limpieza de error de duplicado en input", campoId);
                error.remove();
            }

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
            let isInvalid = false;
            
            if (input.tagName === "SELECT") {
                if (input.multiple) {
                    isInvalid = input.selectedOptions.length === 0;
                } else {
                    isInvalid = input.selectedIndex === 0 || !value;
                }
            } else {
                isInvalid = !value;
            }

            // Campos opcionales no son obligatorios
            if (campo.optional) {
                return; // Saltar validación para campos opcionales
            }

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

        // Validar campos de garantía
        if (!validarGarantia()) {
            todosValidos = false;
        }

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

            let isInvalid = false;
            if (input.tagName === "SELECT") {
                if (input.multiple) {
                    isInvalid = input.selectedOptions.length === 0;
                } else {
                    isInvalid = input.selectedIndex === 0 || !value;
                }
            } else {
                isInvalid = !value;
            }

            // Campos opcionales no son obligatorios
            if (campo.optional) {
                return; // Saltar validación para campos opcionales
            }

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

        // Validar campos de garantía
        if (!validarGarantia()) {
            valid = false;
            const unidadTiempoSelect = document.getElementById("unidad_tiempo_garantia");
            const parent = unidadTiempoSelect.parentElement.classList.contains("flex")
                ? unidadTiempoSelect.parentElement.parentElement
                : unidadTiempoSelect.parentElement;
                
            const msg = document.createElement("p");
            msg.className = "text-red-500 text-sm mt-1 error-msg";
            msg.innerText = "Debe seleccionar una unidad de tiempo para la garantía.";
            parent.appendChild(msg);
        }

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
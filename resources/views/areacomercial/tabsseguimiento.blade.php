<x-layout.default>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Gestión Dinámica de Cliente Potencial</h2>

        {{-- Tabs --}}
        <div class="flex space-x-4 border-b mb-4">
            <button id="tabEmpresaBtn" class="tab-btn active-tab">Empresa</button>
            <button id="tabContactoBtn" class="tab-btn">Contacto</button>
        </div>

        {{-- Empresa --}}
        <div id="tabEmpresa" class="tab-content">
            <h3 class="text-lg font-semibold mb-4">Registrar / Actualizar Empresa</h3>
            <form id="formEmpresa" class="space-y-4">
                <div>
                    <label class="block font-medium">Razón Social</label>
                    <input type="text" name="razon_social" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block font-medium">RUC</label>
                    <input type="text" name="ruc" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block font-medium">Ubicación</label>
                    <input type="text" name="ubicacion" class="w-full border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded" id="btnGuardarEmpresa">Guardar Empresa</button>
                <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded" id="btnActualizarEmpresa" style="display:none;">Actualizar Empresa</button>
            </form>
        </div>

        {{-- Contacto --}}
        <div id="tabContacto" class="tab-content hidden">
            <h3 class="text-lg font-semibold mb-4">Contactos Vinculados a la Empresa</h3>

            {{-- Formulario para agregar/editar contactos --}}
            <div id="addContactSection" class="hidden mt-4">
                <h4 class="text-md font-semibold mb-2" id="contactFormTitle">Agregar Contactos</h4>
                <form id="formContacto" class="space-y-4">
                    <input type="hidden" name="index" value="">
                    <div>
                        <label class="block font-medium">Nombre Completo</label>
                        <input type="text" name="nombre" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium">Correo</label>
                        <input type="email" name="correo" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-medium">Cargo</label>
                        <input type="text" name="cargo" class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded" id="contactSubmitBtn">Agregar Contacto</button>
                    <button type="button" id="cancelEditBtn" class="bg-gray-500 text-white px-4 py-2 rounded hidden">Cancelar</button>
                </form>

                {{-- Lista dinámica de contactos agregados --}}
                <div class="mt-8">
                    <ul id="contactList" class="list-disc list-inside text-gray-700"></ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tab-btn {
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            color: #4B5563;
            transition: all 0.2s;
            cursor: pointer;
        }

        .active-tab {
            color: #1D4ED8;
            border-color: #1D4ED8;
        }

        .tab-content {
            transition: all 0.3s;
        }

        .contact-actions button {
            margin-left: 0.5rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .btn-edit {
            background-color: #2563EB; /* azul */
            color: white;
        }

        .btn-delete {
            background-color: #DC2626; /* rojo */
            color: white;
        }
    </style>

    <script>
        const tabContactoBtn = document.getElementById('tabContactoBtn');
        const tabEmpresaBtn = document.getElementById('tabEmpresaBtn');
        const tabContacto = document.getElementById('tabContacto');
        const tabEmpresa = document.getElementById('tabEmpresa');
        const addContactSection = document.getElementById('addContactSection');
        const contactList = document.getElementById('contactList');
        const formContacto = document.getElementById('formContacto');
        const contactFormTitle = document.getElementById('contactFormTitle');
        const contactSubmitBtn = document.getElementById('contactSubmitBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const formEmpresa = document.getElementById('formEmpresa');
        const btnGuardarEmpresa = document.getElementById('btnGuardarEmpresa');
        const btnActualizarEmpresa = document.getElementById('btnActualizarEmpresa');

        let contactos = [];
        let editIndex = null;

        // Aquí puedes poner null o datos si ya existe empresa (simulando)
        let empresa = null; // si no existe empresa, pon null
        // let empresa = {
        //     razon_social: "GKM TECHNOLOGY S.A.C",
        //     ruc: "20203243332",
        //     ubicacion: ""
        // };

        // Contacto principal (si tienes contacto principal existente)
        const contactoPrincipal = {
            nombre: "Juan Pérez",
            correo: "juan.perez@email.com",
            cargo: "Gerente Comercial"
        };

        window.onload = () => {
            if(empresa){
                // Empresa existe: llenar formulario, mostrar botón actualizar
                formEmpresa.razon_social.value = empresa.razon_social;
                formEmpresa.ruc.value = empresa.ruc;
                formEmpresa.ubicacion.value = empresa.ubicacion;

                btnGuardarEmpresa.style.display = "none";
                btnActualizarEmpresa.style.display = "inline-block";

                // Inicializar contactos con contacto principal
                contactos.push(contactoPrincipal);

                // Mostrar sección agregar contacto
                addContactSection.classList.remove('hidden');
                renderContactos();
            } else {
                // Empresa no existe: formulario vacío, mostrar botón guardar
                btnGuardarEmpresa.style.display = "inline-block";
                btnActualizarEmpresa.style.display = "none";
                addContactSection.classList.add('hidden');
            }
        };

        function showTab(tab) {
            tabEmpresa.classList.add('hidden');
            tabContacto.classList.add('hidden');
            tabEmpresaBtn.classList.remove('active-tab');
            tabContactoBtn.classList.remove('active-tab');

            if (tab === 'empresa') {
                tabEmpresa.classList.remove('hidden');
                tabEmpresaBtn.classList.add('active-tab');
            } else {
                tabContacto.classList.remove('hidden');
                tabContactoBtn.classList.add('active-tab');
            }
        }

        tabEmpresaBtn.addEventListener('click', () => showTab('empresa'));
        tabContactoBtn.addEventListener('click', () => showTab('contacto'));

        // Guardar empresa (primer registro)
        btnGuardarEmpresa.addEventListener('click', e => {
            e.preventDefault();
            empresa = {
                razon_social: formEmpresa.razon_social.value.trim(),
                ruc: formEmpresa.ruc.value.trim(),
                ubicacion: formEmpresa.ubicacion.value.trim()
            };

            alert("✅ Empresa registrada correctamente.");

            // Mostrar botón actualizar y ocultar guardar
            btnGuardarEmpresa.style.display = "none";
            btnActualizarEmpresa.style.display = "inline-block";

            // Mostrar sección para agregar contactos
            addContactSection.classList.remove('hidden');

            // Inicializar contactos con contacto principal
            contactos = [contactoPrincipal];
            renderContactos();

            // Cambiar a tab contacto
            showTab('contacto');
        });

        // Actualizar empresa
        btnActualizarEmpresa.addEventListener('click', () => {
            empresa.razon_social = formEmpresa.razon_social.value.trim();
            empresa.ruc = formEmpresa.ruc.value.trim();
            empresa.ubicacion = formEmpresa.ubicacion.value.trim();

            alert("✅ Datos de empresa actualizados.");
        });

        // Formulario contactos
        formContacto.addEventListener('submit', e => {
            e.preventDefault();

            const form = e.target;
            const nombre = form.nombre.value.trim();
            const correo = form.correo.value.trim();
            const cargo = form.cargo.value.trim();

            if(!nombre){
                alert("El nombre es obligatorio.");
                return;
            }

            if(editIndex !== null){
                contactos[editIndex] = { nombre, correo, cargo };
                alert("✅ Contacto actualizado.");
            } else {
                contactos.push({ nombre, correo, cargo });
                alert("✅ Contacto agregado.");
            }

            renderContactos();
            resetFormContacto();
        });

        cancelEditBtn.addEventListener('click', () => {
            resetFormContacto();
        });

        function renderContactos() {
            contactList.innerHTML = '';

            if (contactos.length === 0) {
                contactList.innerHTML = '<li>No hay contactos registrados.</li>';
                return;
            }

            contactos.forEach((c, i) => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <span>${c.nombre} - ${c.correo} - ${c.cargo}</span>
                    <span class="contact-actions">
                        <button class="btn-edit" data-index="${i}">Editar</button>
                        <button class="btn-delete" data-index="${i}">Eliminar</button>
                    </span>
                `;
                contactList.appendChild(li);
            });

            // Agregar eventos a botones editar y eliminar
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', e => {
                    const index = e.target.dataset.index;
                    loadContactoToForm(index);
                });
            });

            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', e => {
                    const index = e.target.dataset.index;
                    if(index == 0){
                        alert("⚠️ No puedes eliminar el contacto principal.");
                        return;
                    }
                    if(confirm("¿Seguro que quieres eliminar este contacto?")){
                        contactos.splice(index, 1);
                        renderContactos();
                        alert("✅ Contacto eliminado.");
                        resetFormContacto();
                    }
                });
            });
        }

        function loadContactoToForm(index) {
            const c = contactos[index];
            formContacto.nombre.value = c.nombre;
            formContacto.correo.value = c.correo;
            formContacto.cargo.value = c.cargo;
            formContacto.index.value = index;
            editIndex = index;

            contactFormTitle.textContent = "Editar Contacto";
            contactSubmitBtn.textContent = "Actualizar Contacto";
            cancelEditBtn.classList.remove('hidden');
        }

        function resetFormContacto() {
            formContacto.reset();
            formContacto.index.value = '';
            editIndex = null;
            contactFormTitle.textContent = "Agregar Contactos";
            contactSubmitBtn.textContent = "Agregar Contacto";
            cancelEditBtn.classList.add('hidden');
        }

        // Mostrar tab empresa por defecto
        showTab('empresa');
    </script>
</x-layout.default>

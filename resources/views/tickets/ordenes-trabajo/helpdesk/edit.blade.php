<x-layout.default>
    <div x-data="{ tab: 'home' }">
        <ul class="grid grid-cols-4 gap-2 sm:flex sm:flex-wrap sm:justify-center mt-3 mb-5 sm:space-x-3">
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'home' }" @click="tab = 'home'">
                    Home
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'profile' }" @click="tab = 'profile'">
                    Profile
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'contact' }" @click="tab = 'contact'">
                    Contact
                </a>
            </li>
            <li>
                <a href="javascript:;"
                    class="p-7 py-3 flex flex-col items-center justify-center rounded-lg bg-[#f1f2f3] hover:bg-success hover:text-white"
                    :class="{ 'bg-success text-white': tab === 'settings' }" @click="tab = 'settings'">
                    Settings
                </a>
            </li>
        </ul>

        <!-- Contenido de los Tabs -->
        <div class="mt-4">
            <div x-show="tab === 'home'">
                <h4 class="font-semibold text-2xl mb-4">We move your world!</h4>
                <p>Contenido de Home...</p>
            </div>
            <div x-show="tab === 'profile'">
                <h4 class="font-semibold text-2xl mb-4">Perfil</h4>
                <p>Contenido del perfil...</p>
            </div>
            <div x-show="tab === 'contact'">
                <h4 class="font-semibold text-2xl mb-4">Contacto</h4>
                <p>Contenido de contacto...</p>
            </div>
            <div x-show="tab === 'settings'">
                <h4 class="font-semibold text-2xl mb-4">Configuraciones</h4>
                <p>Contenido de configuraciones...</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</x-layout.default>

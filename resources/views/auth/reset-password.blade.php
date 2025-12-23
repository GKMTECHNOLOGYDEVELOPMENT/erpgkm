{{-- resources/views/auth/reset-password.blade.php --}}
<x-layout.auth>

    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <img src="/assets/images/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />
            <div class="relative flex w-full max-w-[1502px] flex-col justify-between overflow-hidden rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 lg:min-h-[758px] lg:flex-row lg:gap-10 xl:gap-0">
                <div
                    class="relative hidden w-full items-center justify-center bg-[linear-gradient(225deg,#1D2671,#C33764)] p-5 lg:inline-flex lg:max-w-[835px] xl:-ms-32 ltr:xl:skew-x-[14deg] rtl:xl:skew-x-[-14deg]">
                    <div
                        class="absolute inset-y-0 w-8 from-primary/10 via-transparent to-transparent ltr:-right-10 ltr:bg-gradient-to-r rtl:-left-10 rtl:bg-gradient-to-l xl:w-16 ltr:xl:-right-20 rtl:xl:-left-20">
                    </div>
                    <div class="ltr:xl:-skew-x-[14deg] rtl:xl:skew-x-[14deg]">
                        <a href="/" class="w-36 block lg:w-72 ms-10">
                            <img src="/assets/images/auth/logogkm.png" alt="Logo" class="w-full" />
                        </a>
                        <div class="mt-24 hidden w-full max-w-[430px] lg:block slide-astronaut">
                            <img src="/assets/images/auth/loginfoto2.png" alt="Cover Image" class="w-full" />
                        </div>
                    </div>
                </div>
                <div class="relative flex w-full flex-col items-center justify-center gap-6 px-4 pb-16 pt-6 sm:px-6 lg:max-w-[667px]">
                    <div class="flex w-full max-w-[440px] items-center gap-2 lg:absolute lg:end-6 lg:top-6 lg:max-w-full">
                        <a href="/" class="block w-8 lg:hidden">
                            <img src="/assets/images/auth/profile.png" alt="Logo" class="w-full" />
                        </a>
                    </div>
                    
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="w-full max-w-[440px] lg:mt-16">
                        <div class="mb-7">
                            <h1 class="mb-3 text-2xl font-bold !leading-snug dark:text-white">Nueva Contraseña</h1>
                            <p class="dark:text-white">Crea una nueva contraseña para tu cuenta.</p>
                        </div>
                        
                       {{-- En la vista de nueva contraseña --}}
<form class="space-y-5" method="POST" action="{{ route('password.update') }}">
    @csrf
    
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="correo" value="{{ $correo }}">  {{-- Ahora sí existe $correo --}}
    
    <div>
        <label for="password" class="dark:text-white">Nueva Contraseña</label>
        <div class="relative text-white-dark">
            <input id="password" name="password" type="password" 
                   placeholder="Ingresa tu nueva contraseña" 
                   class="form-input ps-10 placeholder:text-white-dark" 
                   required />
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div>
        <label for="password_confirmation" class="dark:text-white">Confirmar Contraseña</label>
        <div class="relative text-white-dark">
            <input id="password_confirmation" name="password_confirmation" type="password" 
                   placeholder="Confirma tu nueva contraseña" 
                   class="form-input ps-10 placeholder:text-white-dark" 
                   required />
        </div>
    </div>

    <button type="submit"
            style="background-color: #8B1E3F; color: white; transition: background-color 0.3s ease;"
            onmouseover="this.style.backgroundColor='#A6274C'"
            onmouseout="this.style.backgroundColor='#8B1E3F'"
            class="w-full rounded-md py-2.5 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FEC8D8]">
        ACTUALIZAR CONTRASEÑA
    </button>
</form>
                        </form>
                    </div>
                    <p class="absolute bottom-6 w-full text-center dark:text-white">
                        © <span id="footer-year">2025</span>. Solutions Force. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layout.auth>
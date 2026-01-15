<!-- Total -->
<div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Entregas</p>
            <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $contadores['total'] }}</p>
            <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">Con entregas registradas</p>
        </div>
        <div class="bg-blue-200 dark:bg-blue-800 p-3 rounded-full">
            <i class="fas fa-boxes text-2xl text-blue-700 dark:text-blue-300"></i>
        </div>
    </div>
</div>

<!-- En Tránsito -->
<div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-warning dark:text-yellow-300">En Tránsito</p>
            <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100 mt-2">{{ $contadores['en_transito'] }}</p>
            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Entregados en uso</p>
        </div>
        <div class="bg-yellow-200 dark:bg-yellow-800 p-3 rounded-full">
            <i class="fas fa-shipping-fast text-2xl text-warning dark:text-yellow-300"></i>
        </div>
    </div>
</div>

<!-- Cedidos (NUEVA TARJETA) -->
<div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Cedidos</p>
            <p class="text-3xl font-bold text-purple-900 dark:text-purple-100 mt-2">{{ $contadores['cedidos'] }}</p>
            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Cedidos a terceros</p>
        </div>
        <div class="bg-purple-200 dark:bg-purple-800 p-3 rounded-full">
            <i class="fas fa-exchange-alt text-2xl text-purple-700 dark:text-purple-300"></i>
        </div>
    </div>
</div>

<!-- Usados -->
<div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-green-700 dark:text-green-300">Usados</p>
            <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $contadores['usados'] }}</p>
            <p class="text-xs text-green-600 dark:text-green-400 mt-1">Ya utilizados</p>
        </div>
        <div class="bg-green-200 dark:bg-green-800 p-3 rounded-full">
            <i class="fas fa-check-circle text-2xl text-green-700 dark:text-green-300"></i>
        </div>
    </div>
</div>

<!-- Devueltos -->
<div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-red-700 dark:text-red-300">Devueltos</p>
            <p class="text-3xl font-bold text-red-900 dark:text-red-100 mt-2">{{ $contadores['devueltos'] }}</p>
            <p class="text-xs text-red-600 dark:text-red-400 mt-1">Devueltos al stock</p>
        </div>
        <div class="bg-red-200 dark:bg-red-800 p-3 rounded-full">
            <i class="fas fa-undo-alt text-2xl text-red-700 dark:text-red-300"></i>
        </div>
    </div>
</div>
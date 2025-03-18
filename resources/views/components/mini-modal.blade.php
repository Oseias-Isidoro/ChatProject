<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-gray-900 p-6 rounded-2xl shadow-lg w-96">
        <!-- Título e Botão Fechar -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-white text-lg font-semibold">{{ $title }}</h2>
            <a href="#" onclick="document.location = document.location.pathname" class="text-white hover:text-gray-400 text-xl">&times;</a>
        </div>

        {{ $slot }}

    </div>
</div>

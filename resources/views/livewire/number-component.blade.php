<div class="bg-gray-900 p-6 rounded-2xl shadow-md hover:bg-gray-800 transition duration-300">
    <!-- Nome do Número e Ícones de Ação -->
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-white">{{ $number->name }}</h3>

        <!-- Botões de Ação (Editar, Conectar/Desconectar e Deletar) -->
        <div class="flex items-center space-x-4">
            <!-- Botão de Editar -->
            <a href="{{ route('numbers.index', ['edit' => $number->id, 'page' => request('page', 1)]) }}" class="text-gray-400 hover:text-blue-500 transition duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </a>

            <!-- Botão de Conectar/Desconectar -->
            @if (!$number->isActive)
                <!-- Botão de Conectar (exibido apenas se inativo) -->
                <a href="{{route('numbers.connect', ['number' => $number->id]) }}" class="text-gray-400 hover:text-green-500 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </a>
            @else
                <!-- Botão de Desconectar (exibido apenas se ativo) -->
                <a href="{{route('numbers.disconnect', ['number' => $number->id]) }}" class="text-gray-400 hover:text-yellow-500 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </a>
            @endif

            <!-- Botão de Deletar -->
            <form method="POST" action="{{ route('numbers.destroy', $number->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-400 hover:text-red-500 transition duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Número de telefone -->
    <p class="text-gray-400 mt-2">{{ $number->phone_number }}</p>

    <!-- Status com Ícone -->
    <div class="mt-4 flex items-center">
        <div class="flex items-center p-2 rounded-full {{ $number->isActive ? 'bg-green-200' : 'bg-gray-700' }}">
            @if ($number->isActive)
                <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @else
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            @endif
        </div>
        <span class="ml-2 {{ $number->isActive ? 'text-green-700' : 'text-gray-400' }}">
                                        {{ $number->isActive ? __('Active') : __('Inactive') }}
                                    </span>
    </div>
</div>

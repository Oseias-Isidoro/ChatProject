<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Botão para abrir o modal de criação -->

                        <x-add-button route="{{route('users.index', ['modal' => 'open'])}}" title="{{ __('Add User') }}"/>

                        @foreach($users as $user)
                            <div class="bg-gray-900 p-6 rounded-2xl shadow-md hover:bg-gray-800 transition duration-300">
                                <!-- Nome do Número e Ícones de Ação -->
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-white">{{ $user->name }}</h3>

                                    <!-- Botões de Ação (Editar, Conectar/Desconectar e Deletar) -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Botão de Editar -->
                                        <a href="{{ route('users.index', ['edit' => $user->id, 'page' => request('page', 1)]) }}" class="text-gray-400 hover:text-blue-500 transition duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>

                                        <!-- Botão de Deletar -->
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}">
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
                                <p class="text-gray-400 mt-2">{{ $user->email }}</p>
                                <p class="text-gray-400 text-xs mt-2">{{ __('Roles') }}: {{ implode(', ', $user->roles->pluck('name')->toArray()) }}</p>
                                <p class="text-gray-400 text-xs mt-2">{{ __('Numbers') }}: {{ implode(', ', $user->numbers->pluck('name')->toArray()) }}</p>

                            </div>
                        @endforeach

                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (request('modal') === 'open')
        <x-mini-modal title="{{ __('Add User') }}">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Name') }} *</label>
                    <input type="text" name="name" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Email') }} *</label>
                    <input type="text" name="email" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('email') }}" required>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Password') }}</label>
                    <input type="password" name="password" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('password') }}" required>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Role') }}</label>
                    <div class="mt-1 relative">
                        <!-- Input e Botão para abrir/fechar o dropdown -->
                        <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                            <input type="text" readonly id="multiselect" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Selecione...">
                            <button type="button" id="dropdown-button" class="p-2 bg-gray-200 rounded-r-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                ▼
                            </button>
                        </div>

                        <!-- Lista de opções -->
                        <div id="dropdown-options" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden">
                            <div class="p-2">

                                @foreach(\App\Enums\UserRolesEnum::all() as $role)
                                    <label class="flex items-center p-2 hover:bg-gray-100 rounded">
                                        <input name="roles[]" type="checkbox" value="{{$role}}" class="mr-2">
                                        <span>{{$role}}</span>
                                    </label>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Área para exibir as opções selecionadas -->
                    <div id="selected-options" class="mt-2"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Numbers') }}</label>
                    <div class="mt-1 relative">
                        <!-- Input e Botão para abrir/fechar o dropdown -->
                        <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                            <input type="text" readonly id="multiselect" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Selecione...">
                            <button type="button" id="dropdown-button-number" class="p-2 bg-gray-200 rounded-r-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                ▼
                            </button>
                        </div>
                        @error('roles')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Lista de opções -->
                        <div id="dropdown-options-number" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden">
                            <div class="p-2">
                                @foreach(\Illuminate\Support\Facades\Auth::user()->account->numbers as $number)
                                    <label class="flex items-center p-2 hover:bg-gray-100 rounded">
                                        <input name="numbers[]" type="checkbox" value="{{$number->id}}" data-number-name="{{$number->name}}" class="mr-2">
                                        <span>{{$number->name}}</span>
                                    </label>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Área para exibir as opções selecionadas -->
                    <div id="selected-options-number" class="mt-2">
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('contacts.index') }}" class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">{{ __('Cancel') }}</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500">{{ __('Save') }}</button>
                </div>
            </form>
        </x-mini-modal>
    @endif

    <!-- Modal de Edição (Aparece apenas se "edit" estiver na URL) -->
    @if (request('edit'))
        @php
            $numberToEdit = $users->firstWhere('id', request('edit'));
        @endphp

        @if ($numberToEdit)
            <x-mini-modal title="{{'Edit User'}}">
                <!-- Formulário de Edição -->
                <form method="POST" action="{{ route('users.update', $numberToEdit->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Name') }}</label>
                        <input type="text" name="name" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ $numberToEdit->name }}" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Email') }} *</label>
                        <input type="text" name="email" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ $numberToEdit->email }}" required>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Password') }}</label>
                        <input type="password" name="password" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="" required>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Role') }}</label>
                        <div class="mt-1 relative">
                            <!-- Input e Botão para abrir/fechar o dropdown -->
                            <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                                <input type="text" readonly id="multiselect" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Selecione...">
                                <button type="button" id="dropdown-button" class="p-2 bg-gray-200 rounded-r-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    ▼
                                </button>
                            </div>
                            @error('roles')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <!-- Lista de opções -->
                            <div id="dropdown-options" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                <div class="p-2">
                                    @php
                                        $user_rules = $numberToEdit->roles->pluck('name')->toArray();
                                    @endphp

                                    @foreach(\App\Enums\UserRolesEnum::all() as $role)
                                        <label class="flex items-center p-2 hover:bg-gray-100 rounded">
                                            <input {{ in_array($role, $user_rules) ? 'checked' : ''  }} name="roles[]" type="checkbox" value="{{$role}}" class="mr-2">
                                            <span>{{$role}}</span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <!-- Área para exibir as opções selecionadas -->
                        <div id="selected-options" class="mt-2">
                            @foreach($user_rules as $role)
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mr-2">{{$role}}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Numbers') }}</label>
                        <div class="mt-1 relative">
                            <!-- Input e Botão para abrir/fechar o dropdown -->
                            <div class="flex items-center border border-gray-300 rounded-md shadow-sm">
                                <input type="text" readonly id="multiselect" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="Selecione...">
                                <button type="button" id="dropdown-button-number" class="p-2 bg-gray-200 rounded-r-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    ▼
                                </button>
                            </div>
                            @error('roles')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            @php
                                $user_numbers = $numberToEdit->numbers;
                            @endphp

                            <!-- Lista de opções -->
                            <div id="dropdown-options-number" class="absolute mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden">
                                <div class="p-2">
                                    @foreach(\Illuminate\Support\Facades\Auth::user()->account->numbers as $number)
                                        <label class="flex items-center p-2 hover:bg-gray-100 rounded">
                                            <input {{ in_array($number->id, $user_numbers->pluck('id')->toArray()) ? 'checked' : '' }} name="numbers[]" type="checkbox" value="{{$number->id}}" data-number-name="{{$number->name}}" class="mr-2">
                                            <span>{{$number->name}}</span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        <!-- Área para exibir as opções selecionadas -->
                        <div id="selected-options-number" class="mt-2">
                            @foreach($user_numbers as $number)
                                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mr-2">{{$number->name}}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('users.index') }}" class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">{{ __('Cancel') }}</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500">{{ __('Update') }}</button>
                    </div>
                </form>
            </x-mini-modal>
        @endif
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('dropdown-button');
            const dropdownOptions = document.getElementById('dropdown-options');
            const selectedOptionsContainer = document.getElementById('selected-options');
            const checkboxes = dropdownOptions.querySelectorAll('input[type="checkbox"]');

            // Abrir/fechar o dropdown
            dropdownButton.addEventListener('click', function () {
                dropdownOptions.classList.toggle('hidden');
            });

            // Fechar o dropdown ao clicar fora
            document.addEventListener('click', function (event) {
                if (!dropdownButton.contains(event.target) && !dropdownOptions.contains(event.target)) {
                    dropdownOptions.classList.add('hidden');
                }
            });

            // Atualizar as opções selecionadas
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateSelectedOptions();
                });
            });

            // Função para atualizar as opções selecionadas
            function updateSelectedOptions() {
                const selectedOptions = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedOptions.push(checkbox.value);
                    }
                });

                selectedOptionsContainer.innerHTML = selectedOptions.map(option => `
                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mr-2">
                    ${option}
                </span>
            `).join('');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('dropdown-button-number');
            const dropdownOptions = document.getElementById('dropdown-options-number');
            const selectedOptionsContainer = document.getElementById('selected-options-number');
            const checkboxes = dropdownOptions.querySelectorAll('input[type="checkbox"]');

            // Abrir/fechar o dropdown
            dropdownButton.addEventListener('click', function () {
                dropdownOptions.classList.toggle('hidden');
            });

            // Fechar o dropdown ao clicar fora
            document.addEventListener('click', function (event) {
                if (!dropdownButton.contains(event.target) && !dropdownOptions.contains(event.target)) {
                    dropdownOptions.classList.add('hidden');
                }
            });

            // Atualizar as opções selecionadas
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateSelectedOptions();
                });
            });

            // Função para atualizar as opções selecionadas
            function updateSelectedOptions() {
                const selectedOptions = [];
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedOptions.push(checkbox.dataset.numberName);
                    }
                });

                selectedOptionsContainer.innerHTML = selectedOptions.map(option => `
                <span class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mr-2">
                    ${option}
                </span>
            `).join('');
            }
        });
    </script>

</x-app-layout>

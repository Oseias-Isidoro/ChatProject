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

                        <x-add-button route="{{route('contacts.index', ['modal' => 'open'])}}" title="{{ __('Add Contact') }}"/>

                        @foreach($contacts as $contact)
                            <div class="bg-gray-900 p-6 rounded-2xl shadow-md hover:bg-gray-800 transition duration-300">
                                <!-- Nome do Número e Ícones de Ação -->
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-white">{{ $contact->name }}</h3>

                                    <!-- Botões de Ação (Editar, Conectar/Desconectar e Deletar) -->
                                    <div class="flex items-center space-x-4">
                                        <!-- Botão de Editar -->
                                        <a href="{{ route('contacts.index', ['edit' => $contact->id, 'page' => request('page', 1)]) }}" class="text-gray-400 hover:text-blue-500 transition duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>

                                        <!-- Botão de Deletar -->
                                        <form method="POST" action="{{ route('contacts.destroy', $contact->id) }}">
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
                                <p class="text-gray-400 mt-2">{{ $contact->phone_number }}</p>

                            </div>
                        @endforeach

                    </div>

                    <div class="mt-3">
                        {{ $contacts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (request('modal') === 'open')
        <x-mini-modal title="{{ __('Add Contact') }}">
            <form method="POST" action="{{ route('contacts.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Name') }} *</label>
                    <input type="text" name="name" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('name') }}" required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Phone Number') }} *</label>
                    <input type="text" name="phone_number" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('phone_number') }}" required>
                    @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Email') }}</label>
                    <input type="email" name="email" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('email') }}" >
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
            $numberToEdit = $contacts->firstWhere('id', request('edit'));
        @endphp

        @if ($numberToEdit)
            <x-mini-modal title="{{'Edit Contact'}}">
                <!-- Formulário de Edição -->
                <form method="POST" action="{{ route('contacts.update', $numberToEdit->id) }}">
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
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone_number" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ $numberToEdit->phone_number }}" required>
                        @error('phone_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Email') }}</label>
                        <input type="email" name="email" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ $numberToEdit->email }}" >
                        @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('contacts.index') }}" class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">{{ __('Cancel') }}</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500">{{ __('Update') }}</button>
                    </div>
                </form>
            </x-mini-modal>
        @endif
    @endif


</x-app-layout>

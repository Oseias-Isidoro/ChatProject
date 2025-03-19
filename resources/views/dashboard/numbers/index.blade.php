<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Numbers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Botão para abrir o modal de criação -->
                        <x-add-button route="{{route('numbers.index', ['modal' => 'open'])}}" title="{{ __('Add Number') }}"/>

                        <livewire:styles />
                        @foreach ($numbers as $number)
                            <livewire:number-component :number="$number"  />
                        @endforeach
                        <livewire:scripts />
                    </div>

                    <div class="mt-3">
                        {{ $numbers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação (Aparece apenas se "modal=open" estiver na URL) -->
    @if (request('modal') === 'open')
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-gray-900 p-6 rounded-2xl shadow-lg w-96">
                <!-- Título e Botão Fechar -->
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-white text-lg font-semibold">{{ __('Add Number') }}</h2>
                    <a href="{{ route('numbers.index') }}" class="text-white hover:text-gray-400 text-xl">&times;</a>
                </div>

                <!-- Formulário de Criação -->
                <form method="POST" action="{{ route('numbers.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Name') }}</label>
                        <input type="text" name="name" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('name') }}" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-400 text-sm font-semibold mb-2">{{ __('Phone Number') }}</label>
                        <input type="text" name="phone_number" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-600" value="{{ old('phone_number') }}" required>
                        @error('phone_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('numbers.index') }}" class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">{{ __('Cancel') }}</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal de Edição (Aparece apenas se "edit" estiver na URL) -->
    @if (request('edit'))
        @php
            $numberToEdit = $numbers->firstWhere('id', request('edit'));
        @endphp

        @if ($numberToEdit)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-gray-900 p-6 rounded-2xl shadow-lg w-96">
                    <!-- Título e Botão Fechar -->
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-white text-lg font-semibold">{{ __('Edit Number') }}</h2>
                        <a href="{{ route('numbers.index') }}" class="text-white hover:text-gray-400 text-xl">&times;</a>
                    </div>

                    <!-- Formulário de Edição -->
                    <form method="POST" action="{{ route('numbers.update', $numberToEdit->id) }}">
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

                        <div class="flex justify-end">
                            <a href="{{ route('numbers.index') }}" class="mr-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">{{ __('Cancel') }}</a>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif

    @if(session('instance_data'))
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-gray-900 p-6 rounded-2xl shadow-lg w-96">
                <!-- Título e Botão Fechar -->
                <div class="flex flex-col mb-4">
                    <div class="flex flex-row items-center justify-between">
                        <h2 class="text-white text-lg font-semibold">{{ __('Connect') }}</h2>
                        <a href="{{ route('numbers.index') }}" class="text-white hover:text-gray-400 text-xl">&times;</a>
                    </div>
                    <div class="flex items-center justify-center mt-5" >
                        @if (!isset(session('instance_data')['status']))
                            <img id="qrcode" src="{{session('instance_data')['qrcode']}}"/>

                            <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
                            <script>
                                const socket = io('http://localhost:8080/{{session('instance_data')['instance_name']}}', {
                                    transports: ['websocket']
                                });

                                socket.on('connect', (data) => {
                                    console.log('Conectado ao WebSocket da Evolution API');
                                });

                                // Escutando eventos
                                socket.on('qrcode.updated', (data) => {
                                    document.getElementById('qrcode').src = data.data.qrcode.base64
                                });

                                socket.on('connection.update', (data) => {

                                    if (data.data.state === "connecting") {
                                        document.getElementById('qrcode').src = 'https://i.gifer.com/VAyR.gif'
                                    } else if (data?.data?.profilePictureUrl) {
                                        let element = document.getElementById('qrcode')
                                        element.classList.add('rounded')
                                        element.src = data.data.profilePictureUrl
                                    }

                                    console.log(data)
                                });


                            </script>
                        @else
                            <h1 class="text-green-600">{{__('Already Connected')}}</h1>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

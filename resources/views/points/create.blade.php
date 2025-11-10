<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Ponto de Descarte') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800">
                    <form action="{{ route('points.store') }}" method="POST">
                        @csrf

                        <!-- Nome -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome do Ponto
                            </label>
                            <input type="text" name="name" id="name"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50"
                                   required>
                        </div>

                        <!-- Endereço (com autocomplete) -->
                        <div class="mb-4">
                            <label for="autocomplete" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Endereço
                            </label>
                            <input type="text" id="autocomplete" name="address"
                                   placeholder="Digite o endereço..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50"
                                   required>
                        </div>

                        <!-- Campos ocultos de coordenadas -->
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">

                        <!-- Tipo -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo de Descarte
                            </label>
                            <input type="text" name="type" id="type"
                                   placeholder="Ex: Eletrônico, Plástico, Vidro..."
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50"
                                   required>
                        </div>

                        <!-- Botão -->
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                {{ __('Criar Ponto') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_PLACES_API_KEY') }}&libraries=places"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('autocomplete');

            if (!input) {
                console.error("Campo de autocomplete não encontrado.");
                return;
            }

            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    alert("Por favor, selecione um local válido da lista.");
                    return;
                }

                document.getElementById('latitude').value = place.geometry.location.lat();
                document.getElementById('longitude').value = place.geometry.location.lng();

                console.log("📍 Local selecionado:", {
                    name: place.name,
                    address: place.formatted_address,
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                });
            });
        });
    </script>
</x-app-layout>

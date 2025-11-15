<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-3xl text-white tracking-tight">
                Pontos de Coleta
            </h2>

            <!-- Botão de Novo Ponto -->
            <a href="{{ route('points.create') }}"
               class="inline-flex items-center px-5 py-3 bg-green-500 hover:bg-green-600 text-black font-semibold rounded-lg shadow-md transition-transform transform hover:scale-105 duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Ponto
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-lg sm:rounded-2xl border border-gray-700">
                <div class="p-6">

                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr class="bg-gray-700 text-gray-300 uppercase text-sm tracking-wider">
                                <th class="px-6 py-4 text-left">Nome</th>
                                <th class="px-6 py-4 text-left">Endereço</th>
                                <th class="px-6 py-4 text-left">Tipo</th>
                                <th class="px-6 py-4 text-left">Verificado</th>
                                <th class="px-6 py-4 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($points as $point)
                                <tr class="hover:bg-gray-700/60 transition duration-300 ease-in-out transform hover:scale-[1.01]">
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $point->name }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ $point->address }}</td>
                                    <td class="px-6 py-4 text-gray-300">{{ $point->type }}</td>
                                    <td class="px-6 py-4 text-gray-300">
                                        @if ($point->verified)
                                            <span class="px-3 py-1 text-xs font-semibold bg-green-600/30 text-green-400 rounded-full">
                                                Verificado
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold bg-yellow-600/30 text-yellow-400 rounded-full">
                                                Pendente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center space-x-3">

                                            {{-- Editar (somente dono ou admin) --}}
                                            @if (auth()->check() && (auth()->user()->is_admin || auth()->id() === $point->user_id))
                                                <a href="{{ route('points.edit', $point->id) }}"
                                                class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg shadow transition-transform transform hover:scale-105 duration-200">
                                                    ✏️ Editar
                                                </a>
                                            @endif

                                            {{-- Excluir (somente dono ou admin) --}}
                                            @if (auth()->check() && (auth()->user()->is_admin || auth()->id() === $point->user_id))
                                                <form action="{{ route('points.destroy', $point->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este ponto?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg shadow transition-transform transform hover:scale-105 duration-200">
                                                        🗑 Excluir
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Aprovar (somente admin e se ainda não verificado) --}}
                                            @if (auth()->check() && auth()->user()->is_admin && !$point->verified)
                                                <form action="{{ route('points.verify', $point->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg shadow transition-transform transform hover:scale-105 duration-200">
                                                        ✅ Aprovar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-400">
                                        Nenhum ponto cadastrado ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Animação suave na entrada -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, i) => {
                row.style.opacity = 0;
                row.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = 1;
                    row.style.transform = 'translateY(0)';
                }, i * 100);
            });
        });
    </script>
</x-app-layout>

<div class="px-4 py-8 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                Categorías de gasto
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
                Administra las categorías de gasto por unidad de negocio y proveedor.
            </p>
        </div>

        <div class="mt-4 sm:mt-0">
            <button
                type="button"
                wire:click="create"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                       focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                Nueva categoría
            </button>
        </div>
    </div>

    {{-- Buscador --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Total categorías:
            <span class="font-semibold text-gray-700 dark:text-gray-200">
                {{ $categories->total() }}
            </span>
        </div>

        <div class="w-full sm:w-72">
            <label for="search" class="sr-only">Buscar</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m15.75 15.75-3-3m0 0a3.75 3.75 0 1 0-5.303-5.303A3.75 3.75 0 0 0 12.75 12.75Z" />
                    </svg>
                </span>
                <input
                    id="search"
                    type="text"
                    wire:model.debounce.500ms="search"
                    placeholder="Buscar por gasto, proveedor, unidad…"
                    class="block w-full rounded-md border border-gray-300 bg-white py-1.5 pl-7 pr-3 text-xs text-gray-900
                           shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="mt-4 -mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                    <thead class="bg-gray-50 dark:bg-gray-950/40">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pl-6 dark:text-gray-300">
                            Unidad de negocio
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Nombre del gasto
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Proveedor
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Estado
                        </th>
                        <th class="py-3.5 pr-4 pl-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pr-6 dark:text-gray-300">
                            Acciones
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                    @forelse($categories as $category)
                        <tr>
                            <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-900 sm:pl-6 dark:text-white">
                                {{ $category->business_unit }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $category->expense_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $category->provider_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-3 text-sm">
                                    <span class="inline-flex items-center rounded-md
                                                 {{ $category->is_active
                                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50'
                                                    : 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/50' }}
                                                 px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                        {{ $category->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                            </td>
                            <td class="whitespace-nowrap py-3 pr-4 pl-3 text-right text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-end gap-4 text-xs text-slate-500">
                                    <button
                                        type="button"
                                        wire:click="edit({{ $category->id }})"
                                        class="inline-flex items-center gap-1 hover:text-sky-600
                                                   hover:underline underline-offset-2 decoration-sky-400 transition">
                                        <i class="fa-thin fa-pen text-[0.9rem]"></i>
                                        <span>Editar</span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $category->id }})"
                                        class="inline-flex items-center gap-1 hover:text-red-600
                                                   hover:underline underline-offset-2 decoration-red-400 transition">
                                        <i class="fa-thin fa-trash text-[0.9rem]"></i>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                Aún no hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3 flex justify-end">
                {{ $categories->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    @include('livewire.modals.category-form')
    @livewire('confirm-modal')
</div>

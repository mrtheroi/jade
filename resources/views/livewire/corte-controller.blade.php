<div class="px-4 py-8 sm:px-6 lg:px-8">
    <form wire:submit.prevent="submit" class="space-y-8">

        {{-- Header --}}
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                    Carga y extracción de archivos
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
                    Sube tus archivos de caja, indica el turno y la fecha,
                    y revisa los montos extraídos en la tabla de resultados.
                </p>
            </div>

            <div class="mt-4 sm:mt-0">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm
                           hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                           focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                    Ver historial de cargas
                </button>
            </div>
        </div>

        {{-- Controles: Turno + Fecha + Unidad de negocio --}}
        <div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 lg:max-w-4xl">
                {{-- Turno --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Turno
                    </label>
                    <div class="mt-1">
                        <select
                            wire:model="turno"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="block w-full rounded-md border-gray-300 bg-white py-2 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona un turno</option>
                            <option value="1">Turno 1</option>
                            <option value="2">Turno 2</option>
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Este turno se asociará a todos los movimientos del archivo cargado.
                    </p>
                    @error('turno')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Fecha
                    </label>
                    <div class="mt-1">
                        <input
                            type="date"
                            wire:model="date"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Fecha operativa del turno (no necesariamente la fecha de carga).
                    </p>
                    @error('date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unidad de negocio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Unidad de negocio
                    </label>
                    <div class="mt-1">
                        <select
                            wire:model="business_unit"
                            wire:loading.attr="disabled"
                            wire:target="submit"
                            class="block w-full rounded-md border-gray-300 bg-white py-2 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona una unidad</option>
                            <option value="Jade">Jade</option>
                            <option value="Fuego Ambar">Fuego Ambar</option>
                            <option value="KIN">KIN</option>
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Selecciona a qué unidad pertenece este corte de caja.
                    </p>
                    @error('business_unit')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


        {{-- Dropzone --}}
        <div>
            <div class="rounded-lg border border-dashed border-gray-300 bg-white px-6 py-10 shadow-sm
                        dark:border-white/15 dark:bg-gray-900">
                <div class="mx-auto max-w-xl text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 9 12 4.5 7.5 9M12 4.5V15" />
                    </svg>

                    <div class="mt-4 flex justify-center text-sm text-gray-600 dark:text-gray-300">
                        <label for="file-upload"
                               class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600
                                      hover:text-indigo-500 dark:bg-gray-900">
                            <span>Selecciona una imagen</span>
                            <input
                                id="file-upload"
                                type="file"
                                class="sr-only"
                                wire:model="file"
                                accept="image/*"
                                wire:loading.attr="disabled"
                                wire:target="submit"
                            >
                        </label>
                        <p class="pl-1">o arrástrala y suéltala aquí</p>
                    </div>

                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Formatos soportados: JPG, JPEG, PNG &middot; Máx. 20MB
                    </p>

                    {{-- Estado de carga de imagen (subida) --}}
                    <div class="mt-4 text-xs">
                        <div wire:loading wire:target="file" class="text-gray-500 dark:text-gray-400">
                            Subiendo imagen…
                        </div>

                        @if($file)
                            <p class="mt-1 text-gray-700 dark:text-gray-200">
                                Imagen seleccionada:
                                <span class="font-medium">
                                    {{ $file->getClientOriginalName() ?? 'Imagen lista' }}
                                </span>
                            </p>
                        @endif

                        @error('file')
                        <p class="mt-1 text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Botón de enviar (procesar) + loader sutil --}}
        <div class="flex items-center gap-3">
            <button
                type="submit"
                class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                       focus-visible:outline-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
                wire:loading.attr="disabled"
                wire:target="submit"
            >
                <svg
                    wire:loading
                    wire:target="submit"
                    class="mr-2 h-4 w-4 animate-spin text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span>Procesar archivo</span>
            </button>

            <div
                class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400"
                wire:loading
                wire:target="submit"
            >
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Procesando extracción…</span>
            </div>
        </div>

        {{-- BUSCADOR + tabla de resultados --}}
        <div class="space-y-3">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Resultados de extracción
                    </h2>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Revisa los montos detectados para cada archivo según turno y fecha.
                    </p>
                </div>

                {{-- Buscador --}}
                <div class="w-full sm:w-64">
                    <label class="sr-only" for="search">Buscar</label>
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
                            placeholder="Buscar por usuario, turno, fecha…"
                            class="block w-full rounded-md border border-gray-300 bg-white py-1.5 pl-7 pr-3 text-xs text-gray-900
                                   shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Total registros:
                    <span class="font-semibold text-gray-700 dark:text-gray-200">
                        {{ $extractions->total() }}
                    </span>
                </span>

                @if($search)
                    <span class="text-[11px] text-gray-400 dark:text-gray-500">
                        Filtro aplicado: "{{ $search }}"
                    </span>
                @endif
            </div>

            <div class="mt-2 -mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden rounded-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-900">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                            <thead class="bg-gray-50 dark:bg-gray-950/40">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pl-6 dark:text-gray-300">
                                    Turno
                                </th>
                                <th class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Monto débito
                                </th>
                                <th class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Monto crédito
                                </th>
                                <th class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Efectivo
                                </th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Estado
                                </th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Fecha de subida
                                </th>
                                <th class="py-3.5 pr-4 pl-3 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                            @forelse($extractions as $extraction)
                                <tr>
                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-900 sm:pl-6 dark:text-white">
                                        Turno {{ $extraction->turno }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format($extraction->monto_debito, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format($extraction->monto_credito, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format($extraction->efectivo, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm">
                                            <span class="inline-flex items-center rounded-md
                                                         {{ $extraction->status === 'validado'
                                                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50'
                                                            : 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/50' }}
                                                         px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                                {{ ucfirst($extraction->status) }}
                                            </span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $extraction->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="whitespace-nowrap py-3 pr-4 pl-3 text-right text-sm font-medium sm:pr-6">
                                        <button
                                            type="button"
                                            wire:click="showDetail({{ $extraction->id }})"
                                            class="text-xs text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            Ver detalle
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Aún no hay datos extraídos. Sube un archivo para comenzar.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-3 flex justify-end">
                        {{ $extractions->onEachSide(1)->links() }}
                    </div>

                    {{-- RESUMEN POR DÍA: suma de turnos 1 + 2 --}}
                    {{-- RESUMEN POR DÍA: suma de turnos 1 + 2 --}}
                    @if($dailyTotals->isNotEmpty())
                        <div class="mt-8 space-y-3">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                Resumen diario (Turno 1 + Turno 2)
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Totales por fecha operativa considerando ambos turnos.
                            </p>

                            <div class="overflow-hidden rounded-lg ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-900">
                                <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-white/10">
                                    <thead class="bg-gray-50 dark:bg-gray-950/40">
                                    <tr>
                                        {{-- Fecha operativa --}}
                                        <th class="py-2.5 pl-4 pr-3 text-left font-semibold uppercase tracking-wide text-gray-500 sm:pl-6 dark:text-gray-300">
                                            Fecha operativa
                                        </th>

                                        {{-- Totales por tipo --}}
                                        <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                            Total débito
                                        </th>
                                        <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                            Total crédito
                                        </th>
                                        <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                            Total efectivo
                                        </th>

                                        {{-- 👉 Gran total (débito + crédito + efectivo) --}}
                                        <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                            Gran total
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                                    @foreach($dailyTotals as $day)
                                        @php
                                            $grandTotal = ($day->total_debito ?? 0)
                                                        + ($day->total_credito ?? 0)
                                                        + ($day->total_efectivo ?? 0);
                                        @endphp

                                        <tr>
                                            {{-- Fecha operativa --}}
                                            <td class="whitespace-nowrap py-2.5 pl-4 pr-3 text-gray-900 sm:pl-6 dark:text-gray-100">
                                                {{ \Carbon\Carbon::parse($day->operation_date)->format('d/m/Y') }}
                                            </td>

                                            {{-- Totales individuales --}}
                                            <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                                $ {{ number_format($day->total_debito, 2) }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                                $ {{ number_format($day->total_credito, 2) }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                                $ {{ number_format($day->total_efectivo, 2) }}
                                            </td>

                                            {{-- Gran total --}}
                                            <td class="whitespace-nowrap px-3 py-2.5 text-right font-semibold text-gray-900 dark:text-gray-100">
                                                $ {{ number_format($grandTotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </form>


    @include('livewire.modals.cash')

</div>

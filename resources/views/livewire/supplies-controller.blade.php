
<div class="px-4 py-8 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                Insumos por unidad de negocio
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
                Registra y consulta las compras de insumos por unidad de negocio, con detalle de proveedor,
                montos y estado de los pagos.
            </p>
        </div>

        <div class="mt-4 sm:mt-0">
            <button
                type="button"
                wire:click="create"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm
                       hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                       focus-visible:outline-indigo-600 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus-visible:outline-indigo-500">
                Nuevo insumo
            </button>
        </div>
    </div>

    {{-- Filtros por fecha (rango) --}}
    <div class="mt-4 grid gap-3 sm:grid-cols-3 lg:max-w-xl">
        {{-- Desde --}}
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                Desde
            </label>
            <div class="mt-1">
                <input
                    type="date"
                    wire:model.live="from_date"
                    class="block w-full rounded-md border border-gray-300 bg-white py-1.5 px-2 text-xs text-gray-900 shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>
        </div>

        {{-- Hasta --}}
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                Hasta
            </label>
            <div class="mt-1">
                <input
                    type="date"
                    wire:model.live="to_date"
                    class="block w-full rounded-md border border-gray-300 bg-white py-1.5 px-2 text-xs text-gray-900 shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500
                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>
        </div>

        {{-- Botón limpiar --}}
        <div class="flex items-end">
            <button
                type="button"
                wire:click="clearDateFilter"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium
                   text-gray-700 shadow-sm hover:bg-gray-50
                   dark:border-white/20 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
            >
                Limpiar fechas
            </button>
        </div>
    </div>


    {{-- Cards: total gastado por unidad de negocio --}}
    @if($totalsByUnit->isNotEmpty())
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($totalsByUnit as $unit)
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="px-4 py-4 sm:px-5">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Unidad de negocio
                                </p>
                                <p class="mt-0.5 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $unit->business_unit }}
                                </p>
                            </div>

                            <div class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 dark:bg-indigo-900/40 dark:text-indigo-200 dark:ring-indigo-500/40">
                                Gastos
                            </div>
                        </div>

                        <div class="mt-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Total gastado
                            </p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                $ {{ number_format($unit->total_amount, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Buscador / resumen --}}
    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-xs text-gray-500 dark:text-gray-400">
            Total registros:
            <span class="font-semibold text-gray-700 dark:text-gray-200">
                {{ $supplies->total() }}
            </span>
        </div>

        <div class="w-full sm:w-80">
            <label for="search" class="sr-only">Buscar</label>
            <div class="relative">
                {{-- Icono lupa --}}
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2">
            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                 fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m15.75 15.75-3-3m0 0a3.75 3.75 0 1 0-5.303-5.303A3.75 3.75 0 0 0 12.75 12.75Z" />
            </svg>
        </span>

                {{-- Input --}}
                <input
                    id="search"
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Buscar por unidad, categoría, proveedor…"
                    class="block w-full rounded-md border border-gray-300 bg-white py-1.5 pl-7 pr-7 text-xs text-gray-900
                   shadow-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500
                   dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >

                {{-- Botón X para limpiar --}}
                @if($search !== '')
                    <button
                        type="button"
                        wire:click="$set('search','')"
                        class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-400
                       hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        <span class="sr-only">Limpiar búsqueda</span>
                        <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.44 9.5l-3.22 3.22a.75.75 0 1 0 1.06 1.06L9.5 10.56l3.22 3.22a.75.75 0 1 0 1.06-1.06L10.56 9.5l3.22-3.22a.75.75 0 0 0-1.06-1.06L9.5 8.44 6.28 5.22Z" />
                        </svg>
                    </button>
                @endif
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
                            Unidad negocio
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Categoría
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Proveedor
                        </th>
                        <th class="px-3 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Monto gasto
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Estatus
                        </th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                            Vencido
                        </th>
                        <th class="py-3.5 pr-4 pl-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pr-6 dark:text-gray-300">
                            Acciones
                        </th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                    @forelse($supplies as $supply)
                        @php
                            $paymentDate = $supply->payment_date;
                            $now = now();
                            $isSameMonth = $paymentDate ? $paymentDate->isSameMonth($now) : false;
                            $isOverdue = $paymentDate
                                ? $paymentDate->lt($now->copy()->startOfMonth()) && $supply->status !== 'pagado'
                                : false;
                        @endphp

                        <tr>
                            {{-- Unidad negocio --}}
                            <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-900 sm:pl-6 dark:text-white">
                                {{ $supply->category?->business_unit ?? '—' }}
                            </td>

                            {{-- Categoría --}}
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $supply->category?->expense_name ?? '—' }}
                            </td>

                            {{-- Proveedor --}}
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                {{ $supply->category?->provider_name ?? $supply->provider_name ?? '—' }}
                            </td>

                            {{-- Monto --}}
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                $ {{ number_format($supply->amount, 2) }}
                            </td>

                            {{-- Estatus --}}
                            <td class="whitespace-nowrap px-3 py-3 text-sm">
                <span class="inline-flex items-center rounded-md
                             @switch($supply->status)
                                @case('pagado')
                                    bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50
                                    @break
                                @case('pendiente')
                                    bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/50
                                    @break
                                @default
                                    bg-gray-50 text-gray-700 ring-gray-400/20 dark:bg-gray-900/40 dark:text-gray-200 dark:ring-gray-500/40
                             @endswitch
                             px-2 py-1 text-xs font-medium ring-1 ring-inset">
                    {{ ucfirst($supply->status ?? '—') }}
                </span>
                            </td>

                            {{-- Vencido / al corriente --}}
                            <td class="whitespace-nowrap px-3 py-3 text-sm">
                                @if($isOverdue)
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/50">
                        Vencido
                    </span>
                                @elseif($isSameMonth && $supply->status === 'pagado')
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50">
                        Pagado (mes corriente)
                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-400/20 dark:bg-gray-900/40 dark:text-gray-200 dark:ring-gray-500/40">
                        En curso
                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="whitespace-nowrap py-3 pr-4 pl-3 text-right text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-end gap-3 text-xs text-slate-500">
                                    {{-- Detalle --}}
                                    <button
                                        type="button"
                                        wire:click="showDetail({{ $supply->id }})"
                                        class="inline-flex items-center gap-1 hover:text-indigo-600
                               hover:underline underline-offset-2 decoration-indigo-400 transition"
                                    >
                                        <i class="fa-thin fa-eye text-[0.9rem]"></i>
                                        <span>Detalle</span>
                                    </button>

                                    {{-- Editar --}}
                                    <button
                                        type="button"
                                        wire:click="edit({{ $supply->id }})"
                                        class="inline-flex items-center gap-1 hover:text-sky-600
                               hover:underline underline-offset-2 decoration-sky-400 transition"
                                    >
                                        <i class="fa-thin fa-pen text-[0.9rem]"></i>
                                        <span>Editar</span>
                                    </button>

                                    {{-- Eliminar --}}
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $supply->id }})"
                                        class="inline-flex items-center gap-1 hover:text-red-600
                               hover:underline underline-offset-2 decoration-red-400 transition"
                                    >
                                        <i class="fa-thin fa-trash text-[0.9rem]"></i>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                Aún no hay insumos registrados. Crea el primero con el botón “Nuevo insumo”.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3 flex justify-end">
                {{ $supplies->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    @include('livewire.modals.form-supplies')
    @include('livewire.modals.detail-supplies')
    @livewire('confirm-modal')
</div>

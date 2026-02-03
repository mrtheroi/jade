<div class="px-4 py-8 sm:px-6 lg:px-8">



        {{-- CAPTURA SUPERIOR --}}
        <div class=" mb-3 rounded-2xl p-5 shadow-sm ring-1 ring-black/5 bg-white
                    dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 dark:ring-white/10">
            <form wire:submit.prevent="submit" class="space-y-8">

                {{-- Header --}}
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                            Corte de caja
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
                            Sube una imagen, procesa la extracción y consulta resultados con filtros. La tabla y el resumen diario respetan los filtros de esta pantalla.
                        </p>
                    </div>
                </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 lg:max-w-5xl">


                {{-- Turno --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Turno (para el corte)</label>
                    <select
                        wire:model="turno"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                               focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                    >
                        <option value="">Selecciona un turno</option>
                        <option value="1">Turno 1</option>
                        <option value="2">Turno 2</option>
                    </select>
                    @error('turno') <p class="mt-1 text-xs text-red-600 dark:text-rose-300">{{ $message }}</p> @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Fecha (para el corte)</label>
                    <input
                        type="date"
                        wire:model="date"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm
                               focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                    >
                    @error('date') <p class="mt-1 text-xs text-red-600 dark:text-rose-300">{{ $message }}</p> @enderror
                </div>

                {{-- Unidad de negocio --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Unidad de negocio (para el corte)</label>
                    <select
                        wire:model="business_unit"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                        class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                               focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                    >
                        <option value="">Selecciona una unidad</option>
                        <option value="Jade">Jade</option>
                        <option value="Fuego Ambar">Fuego Ambar</option>
                        <option value="KIN">KIN</option>
                    </select>
                    @error('business_unit') <p class="mt-1 text-xs text-red-600 dark:text-rose-300">{{ $message }}</p> @enderror
                </div>

            </div>

            {{-- Dropzone / file --}}
            <div class="mt-4 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-8
                        dark:border-white/15 dark:bg-white/5">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center text-gray-400 dark:text-white/50">
                        <i class="fa-thin fa-cloud-arrow-up text-3xl"></i>
                    </div>

                    <div class="mt-3 text-sm text-gray-700 dark:text-white/80">
                        <label for="file-upload" class="cursor-pointer font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-300 dark:hover:text-indigo-200">
                            Selecciona una imagen
                        </label>
                        <span class="text-gray-500 dark:text-white/50"> o arrástrala y suéltala aquí</span>
                    </div>

                    <p class="mt-1 text-xs text-gray-500 dark:text-white/50">
                        Formatos soportados: JPG, JPEG, PNG · Máx. 20MB
                    </p>

                    <input
                        id="file-upload"
                        type="file"
                        class="sr-only"
                        wire:model="file"
                        accept="image/*"
                        wire:loading.attr="disabled"
                        wire:target="submit"
                    >

                    <div class="mt-3 text-xs">
                        <div wire:loading wire:target="file" class="text-gray-500 dark:text-white/50">
                            Subiendo imagen…
                        </div>

                        @if($file)
                            <p class="text-gray-700 dark:text-white/70">
                                Archivo seleccionado:
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $file->getClientOriginalName() ?? 'Imagen lista' }}</span>
                            </p>
                        @endif

                        @error('file') <p class="mt-1 text-red-600 dark:text-rose-300">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm
                           hover:bg-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                >
                    <i class="fa-thin fa-bolt mr-2"></i>
                    Procesar archivo
                </button>

                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-white/50" wire:loading wire:target="submit">
                    <i class="fa-thin fa-spinner-third animate-spin"></i>
                    Procesando extracción…
                </div>
            </div>
        </div>


        {{-- RESULTADOS / HISTORIAL (estilo Supplies, pero compatible light/dark) --}}
        <div id="historial" class="space-y-3 scroll-mt-24">

            <div class="rounded-2xl p-5 shadow-sm ring-1 ring-black/5 bg-white
                        dark:bg-gradient-to-br dark:from-slate-900 dark:to-slate-950 dark:ring-white/10">

                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Resultados</h2>

                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700
                                         dark:bg-white/10 dark:text-white/90">
                                {{ ($date_from ?: now()->subDays(30)->toDateString()) }} · {{ ($date_to ?: now()->toDateString()) }}
                            </span>
                        </div>

                        <p class="mt-1 text-sm text-gray-600 dark:text-white/60">
                            Filtra por rango de fechas, unidad, turno y estado. Aplica a la tabla y resumen diario.
                        </p>
                    </div>

                    <div class="shrink-0 text-right">
                        <div class="text-sm text-gray-600 dark:text-white/60">
                            Total: <span class="font-semibold text-gray-900 dark:text-white">{{ $extractions->total() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Row 1: Buscar + filtros principales --}}
                <div class="mt-4 grid gap-3 lg:grid-cols-12">

                    {{-- Buscar --}}
                    <div class="lg:col-span-6">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Buscar</label>
                        <div class="mt-1 relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-white/40">
                                <i class="fa-thin fa-magnifying-glass"></i>
                            </span>
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="Buscar por usuario, unidad, turno, estado…"
                                class="w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-9 pr-3 text-sm text-gray-900 shadow-sm placeholder:text-gray-400
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-white/30 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                            />
                        </div>
                    </div>

                    {{-- Unidad --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Unidad</label>
                        <select
                            wire:model.live="filter_business_unit"
                            class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                        >
                            <option value="">Todas</option>
                            <option value="Jade">Jade</option>
                            <option value="Fuego Ambar">Fuego Ambar</option>
                            <option value="KIN">KIN</option>
                        </select>
                    </div>

                    {{-- Turno --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Turno</label>
                        <select
                            wire:model.live="filter_turno"
                            class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                        >
                            <option value="">Todos</option>
                            <option value="1">Turno 1</option>
                            <option value="2">Turno 2</option>
                        </select>
                    </div>

                    {{-- Estado --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Estado</label>
                        <select
                            wire:model.live="filter_status"
                            class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 pl-3 pr-10 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                        >
                            <option value="">Todos</option>
                            <option value="procesado">Procesado</option>
                            <option value="validado">Validado</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                </div>

                {{-- Row 2: rango + acciones --}}
                <div class="mt-4 grid gap-3 lg:grid-cols-12">

                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Desde</label>
                        <input
                            type="date"
                            wire:model.live="date_from"
                            class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                        >
                    </div>

                    <div class="lg:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 dark:text-white/70">Hasta</label>
                        <input
                            type="date"
                            wire:model.live="date_to"
                            class="mt-1 w-full rounded-lg border border-gray-200 bg-white py-2.5 px-3 text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/10 dark:bg-white/5 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                        >
                    </div>

                    <div class="hidden lg:block lg:col-span-5"></div>

                    <div class="lg:col-span-3 flex items-end justify-end gap-2">
                        <button
                            type="button"
                            wire:click="$set('date_from','{{ now()->subDays(30)->toDateString() }}'); $set('date_to','{{ now()->toDateString() }}')"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition
                                   dark:bg-white/10 dark:text-white dark:hover:bg-white/15"
                        >
                            <i class="fa-thin fa-calendar-days"></i>
                            Últimos 30 días
                        </button>

                        <button
                            type="button"
                            wire:click="$set('date_from','{{ now()->toDateString() }}'); $set('date_to','{{ now()->toDateString() }}')"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-3 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition
                                   dark:bg-white/10 dark:text-white dark:hover:bg-white/15"
                        >
                            <i class="fa-thin fa-calendar-day"></i>
                            Hoy
                        </button>
                    </div>
                </div>

                {{-- Chips + limpiar --}}
                @php
                    $chips = [];
                    if (trim($this->search) !== '') $chips[] = ['icon' => 'fa-magnifying-glass', 'label' => "Buscar: {$this->search}"];
                    if ($this->filter_business_unit) $chips[] = ['icon' => 'fa-building', 'label' => "Unidad: {$this->filter_business_unit}"];
                    if ($this->filter_turno) $chips[] = ['icon' => 'fa-clock', 'label' => "Turno: {$this->filter_turno}"];
                    if ($this->filter_status) $chips[] = ['icon' => 'fa-badge-check', 'label' => "Estado: {$this->filter_status}"];
                    if ($this->date_from || $this->date_to) {
                        $chips[] = ['icon' => 'fa-calendar', 'label' => "Rango: " . ($this->date_from ?: '—') . " → " . ($this->date_to ?: '—')];
                    }
                @endphp

                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @foreach($chips as $c)
                        <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700
                                     dark:bg-white/10 dark:text-white/90">
                            <i class="fa-thin {{ $c['icon'] }} text-[12px] text-gray-500 dark:text-white/70"></i>
                            {{ $c['label'] }}
                        </span>
                    @endforeach

                        <button
                            type="button"
                            wire:click="resetFilters"
                            class="ml-1 inline-flex items-center gap-2 rounded-full bg-gray-900 px-4 py-1.5 text-xs font-semibold text-white hover:bg-black transition
           dark:bg-white dark:text-slate-900 dark:hover:bg-white/90"
                        >
                            <i class="fa-thin fa-broom-wide"></i>
                            Limpiar
                        </button>
                </div>
            </div>




{{-- Tabla --}}
            <div class="relative">
                {{-- overlay loading filtros --}}
                <div
                    wire:loading.flex
                    wire:target="search,filter_business_unit,filter_turno,filter_status,date_from,date_to"
                    class="absolute inset-0 z-20 items-center justify-center bg-white/60 dark:bg-black/40 backdrop-blur-sm rounded-2xl"
                >
                    <div class="inline-flex items-center gap-2 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm
                                dark:bg-gray-900 dark:text-gray-200 dark:border dark:border-white/10">
                        <i class="fa-thin fa-spinner-third animate-spin"></i>
                        Filtrando…
                    </div>
                </div>

                <div class="overflow-hidden rounded-2xl ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/10">
                            <thead class="bg-gray-50 dark:bg-gray-950/40">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 sm:pl-6 dark:text-gray-300">
                                    Fecha operativa
                                </th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Unidad
                                </th>
                                <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
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
                                    Caja
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
                                <tr class="hover:bg-gray-50/60 dark:hover:bg-white/5">
                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-900 sm:pl-6 dark:text-white">
                                        {{ \Carbon\Carbon::parse($extraction->operation_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                        {{ $extraction->business_unit ?? '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-900 dark:text-white">
                                        Turno {{ $extraction->turno }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format((float)$extraction->monto_debito, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format((float)$extraction->monto_credito, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        $ {{ number_format((float)$extraction->efectivo, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm">
                                        @php
                                            $status = $extraction->status ?? 'procesado';
                                            $statusClass = match($status) {
                                                'validado' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50',
                                                'error' => 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-900/30 dark:text-rose-300 dark:ring-rose-500/50',
                                                default => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/50',
                                            };
                                        @endphp

                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm">
                                        @if(($extraction->status ?? null) === 'validado' && $extraction->cash_validation_result)
                                            @php
                                                $r = $extraction->cash_validation_result;
                                                $classes = match($r) {
                                                    'cuadro' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50',
                                                    'faltante' => 'bg-rose-50 text-rose-700 ring-rose-600/20 dark:bg-rose-900/30 dark:text-rose-300 dark:ring-rose-500/50',
                                                    'sobrante' => 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/50',
                                                    default => 'bg-gray-50 text-gray-700 ring-gray-600/20 dark:bg-gray-900/30 dark:text-gray-200 dark:ring-white/10',
                                                };
                                                $label = match($r) {
                                                    'cuadro' => 'Cuadró',
                                                    'faltante' => 'Faltante',
                                                    'sobrante' => 'Sobrante',
                                                    default => '—',
                                                };
                                            @endphp

                                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $classes }}">
                                                    {{ $label }}
                                                </span>
                                        @else
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Pendiente</span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-3 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $extraction->created_at?->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="whitespace-nowrap py-3 pr-4 pl-3 text-right text-sm font-medium sm:pr-6">
                                        <button
                                            type="button"
                                            wire:click="showDetail({{ $extraction->id }})"
                                            class="group relative inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900
                                                       dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white transition"
                                            aria-label="Ver detalle"
                                        >
                                            <i class="fa-thin fa-eye fa-fw text-[14px]"></i>
                                            <span class="pointer-events-none absolute -top-9 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[11px] text-white opacity-0
                                                           shadow-sm transition group-hover:opacity-100 dark:bg-black">
                                                    Ver detalle
                                                </span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay registros con los filtros actuales.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 sm:px-6">
                        {{ $extractions->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>

            {{-- Resumen diario --}}
            @if($dailyTotals->isNotEmpty())
                <div class="mt-8 space-y-3">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Resumen diario (Turno 1 + Turno 2)
                    </h3>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Totales por fecha operativa considerando ambos turnos.
                    </p>

                    <div class="overflow-hidden rounded-2xl ring-1 ring-black/5 dark:ring-white/10 bg-white dark:bg-gray-900">
                        <table class="min-w-full divide-y divide-gray-200 text-xs dark:divide-white/10">
                            <thead class="bg-gray-50 dark:bg-gray-950/40">
                            <tr>
                                <th class="py-2.5 pl-4 pr-3 text-left font-semibold uppercase tracking-wide text-gray-500 sm:pl-6 dark:text-gray-300">
                                    Fecha operativa
                                </th>
                                <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Total débito
                                </th>
                                <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Total crédito
                                </th>
                                <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Total efectivo
                                </th>
                                <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Total propinas
                                </th>
                                <th class="px-3 py-2.5 text-right font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">
                                    Gran total
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-white/10 dark:bg-gray-900">
                            @foreach($dailyTotals as $day)
                                @php
                                    $grandTotal = ((float)($day->total_debito ?? 0))
                                                + ((float)($day->total_credito ?? 0))
                                                + ((float)($day->total_efectivo ?? 0));
                                @endphp

                                <tr>
                                    <td class="whitespace-nowrap py-2.5 pl-4 pr-3 text-gray-900 sm:pl-6 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($day->operation_date)->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                        $ {{ number_format((float)$day->total_debito, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                        $ {{ number_format((float)$day->total_credito, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                        $ {{ number_format((float)$day->total_efectivo, 2) }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2.5 text-right text-gray-900 dark:text-gray-100">
                                        $ {{ number_format((float)$day->total_propina, 2) }}
                                    </td>

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

    </form>

    {{-- ✅ NO TOCAR MODAL --}}
    @include('livewire.modals.cash')

</div>

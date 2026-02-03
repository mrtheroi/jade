<div class="space-y-6">

    {{-- HEADER --}}
    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">Dashboard de ventas</h2>

                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-[11px] font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        {{ $this->business_unit ?: 'Todas' }} · {{ $this->period_key }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Rango: <span class="font-medium">{{ $this->from_date }}</span> → <span class="font-medium">{{ $this->to_date }}</span>
                </p>
            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-wrap items-center gap-2">
                {{-- Export Excel --}}
                <a
                    href="{{ route('dashboard.ventas.export.excel', [
                        'from'          => $this->from_date,
                        'to'            => $this->to_date,
                        'business_unit' => $this->business_unit,
                    ]) }}"
                    class="group relative inline-flex items-center justify-center rounded-md p-2 text-emerald-600 hover:bg-emerald-50 transition
                           dark:text-emerald-300 dark:hover:bg-emerald-900/30"
                    aria-label="Exportar Excel"
                    title="Exportar Excel"
                >
                    <i class="fa-thin fa-file-excel fa-fw text-[15px]"></i>
                </a>

                {{-- Export PDF --}}
                <a
                    href="{{ route('dashboard.ventas.export.pdf', [
                        'from'          => $this->from_date,
                        'to'            => $this->to_date,
                        'business_unit' => $this->business_unit,
                    ]) }}"
                    class="group relative inline-flex items-center justify-center rounded-md p-2 text-rose-600 hover:bg-rose-50 transition
                           dark:text-rose-300 dark:hover:bg-rose-900/30"
                    aria-label="Exportar PDF"
                    title="Exportar PDF"
                >
                    <i class="fa-thin fa-file-pdf fa-fw text-[15px]"></i>
                </a>

                {{-- Limpiar --}}
                <button
                    type="button"
                    wire:click="clearFilters"
                    class="inline-flex items-center justify-center rounded-md border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50 transition
                           dark:border-white/10 dark:text-gray-100 dark:hover:bg-white/5"
                >
                    <i class="fa-thin fa-broom-wide mr-2"></i>
                    Limpiar
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="mt-4 grid gap-3 lg:grid-cols-12">
            {{-- Unidad --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Unidad</label>
                <select
                    wire:model.live="business_unit"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todas</option>
                    <option value="Jade">Jade</option>
                    <option value="Fuego Ambar">Fuego Ambar</option>
                    <option value="KIN">KIN</option>
                </select>
            </div>

            {{-- Periodo --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Periodo</label>
                <input
                    type="month"
                    wire:model.live="period_key"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
            </div>

            {{-- Desde --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Desde</label>
                <input
                    type="date"
                    wire:model.live="from_date"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
            </div>

            {{-- Hasta --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                <input
                    type="date"
                    wire:model.live="to_date"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
            </div>
        </div>

        {{-- CHIPS --}}
        @php
            $chips = [];
            if ($this->business_unit) $chips[] = ['icon' => 'fa-building', 'label' => "Unidad: {$this->business_unit}"];
            if ($this->period_key) $chips[] = ['icon' => 'fa-calendar', 'label' => "Periodo: {$this->period_key}"];
            if ($this->from_date || $this->to_date) $chips[] = ['icon' => 'fa-calendar-range', 'label' => "Rango: {$this->from_date} → {$this->to_date}"];
        @endphp

        @if(count($chips))
            <div class="mt-3 flex flex-wrap items-center gap-2">
                @foreach($chips as $c)
                    <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-[11px] text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <i class="fa-thin {{ $c['icon'] }} text-[12px]"></i>
                        {{ $c['label'] }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- CARDS --}}
    <div class="grid gap-3 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total ventas</p>
                <i class="fa-thin fa-chart-line text-gray-400"></i>
            </div>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($this->totalSales, 2) }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Efectivo</p>
                <i class="fa-thin fa-money-bill-wave text-gray-400"></i>
            </div>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($this->totalCash, 2) }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Débito</p>
                <i class="fa-thin fa-credit-card text-gray-400"></i>
            </div>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($this->totalDebit, 2) }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Crédito</p>
                <i class="fa-thin fa-credit-card-front text-gray-400"></i>
            </div>
            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($this->totalCredit, 2) }}
            </p>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="grid gap-6 lg:grid-cols-2">

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                Ventas por unidad de negocio
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Total de ventas (efectivo + débito + crédito) por unidad.
            </p>
            <div class="mt-4" wire:ignore>
                <canvas id="chartByUnit" class="w-full h-64"></canvas>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                Ventas por método de pago
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Distribución por efectivo, débito y crédito.
            </p>
            <div class="mt-4" wire:ignore>
                <canvas id="chartByMethod" class="w-full h-64"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <script>
            (function () {
                let chartByUnit = null;
                let chartByMethod = null;

                function normalizePayload(payload) {
                    if (payload && payload.data) payload = payload.data;

                    const labelsUnits  = Array.isArray(payload?.labelsUnits)  ? payload.labelsUnits  : [];
                    const dataUnits    = Array.isArray(payload?.dataUnits)    ? payload.dataUnits    : [];
                    const methodLabels = Array.isArray(payload?.methodLabels) ? payload.methodLabels : [];
                    const methodData   = Array.isArray(payload?.methodData)   ? payload.methodData   : [];

                    return { labelsUnits, dataUnits, methodLabels, methodData };
                }

                function destroyChartsIfAny() {
                    if (chartByUnit) { chartByUnit.destroy(); chartByUnit = null; }
                    if (chartByMethod) { chartByMethod.destroy(); chartByMethod = null; }
                }

                function ensureCharts(rawPayload) {
                    const unitCanvas = document.getElementById('chartByUnit');
                    const methodCanvas = document.getElementById('chartByMethod');

                    // Si no estamos en la vista, salimos
                    if (!unitCanvas || !methodCanvas) return;

                    // Si el canvas fue reemplazado por Livewire, el Chart viejo queda “huérfano”
                    // => lo destruimos y lo recreamos limpio.
                    destroyChartsIfAny();

                    const ctxUnit = unitCanvas.getContext('2d');
                    const ctxMethod = methodCanvas.getContext('2d');

                    const { labelsUnits, dataUnits, methodLabels, methodData } = normalizePayload(rawPayload);

                    chartByUnit = new Chart(ctxUnit, {
                        type: 'bar',
                        data: {
                            labels: labelsUnits,
                            datasets: [{ label: 'Total vendido', data: dataUnits, borderWidth: 1 }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    chartByMethod = new Chart(ctxMethod, {
                        type: 'doughnut',
                        data: {
                            labels: methodLabels,
                            datasets: [{ data: methodData, borderWidth: 1 }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                }

                function updateCharts(rawPayload) {
                    const unitCanvas = document.getElementById('chartByUnit');
                    const methodCanvas = document.getElementById('chartByMethod');

                    if (!unitCanvas || !methodCanvas) return;

                    const { labelsUnits, dataUnits, methodLabels, methodData } = normalizePayload(rawPayload);

                    // Si por navegación los charts no existen, los creamos
                    if (!chartByUnit || !chartByMethod) {
                        ensureCharts(rawPayload);
                        return;
                    }

                    chartByUnit.data.labels = labelsUnits;
                    chartByUnit.data.datasets[0].data = dataUnits;
                    chartByUnit.update();

                    chartByMethod.data.labels = methodLabels;
                    chartByMethod.data.datasets[0].data = methodData;
                    chartByMethod.update();
                }

                // 1) Cuando Livewire navega y vuelve a esta vista
                window.addEventListener('livewire:navigated', () => {
                    // Tomamos payload inicial desde la vista (inyectado con json)
                    const initialPayload = {
                    labelsUnits:  @json($labelsUnits ?? []),
                    dataUnits:    @json($dataUnits ?? []),
                    methodLabels: @json($methodLabels ?? []),
                    methodData:   @json($methodData ?? []),
                };

                // Espera un tick a que el DOM esté listo (por navegación Livewire)
                setTimeout(() => ensureCharts(initialPayload), 0);
            });

            // 2) Cambios por filtros (evento desde el componente)
            document.addEventListener('livewire:init', () => {
                Livewire.on('chart-data-updated', (event) => {
                    updateCharts(event.data);
                });
            });

            // 3) Primera carga normal (por si entras directo con refresh)
            document.addEventListener('DOMContentLoaded', () => {
                const initialPayload = {
                    labelsUnits:  @json($labelsUnits ?? []),
                    dataUnits:    @json($dataUnits ?? []),
                    methodLabels: @json($methodLabels ?? []),
                    methodData:   @json($methodData ?? []),
                };
                ensureCharts(initialPayload);
            });
            })();
        </script>
    @endpush


</div>

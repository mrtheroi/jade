<div class="px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    {{-- Header --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-base font-semibold text-gray-900 dark:text-white">
                Dashboard de ventas
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 max-w-2xl">
                Resumen de ventas por unidad de negocio y método de pago.
            </p>
        </div>

        {{-- Filtros por fecha --}}
        <div class="mt-4 flex flex-wrap items-end gap-3 sm:mt-0">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                    Desde
                </label>
                <input
                    type="date"
                    wire:model.live="from_date"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 px-2 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                    Hasta
                </label>
                <input
                    type="date"
                    wire:model.live="to_date"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-1.5 px-2 text-xs text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>

            <div class="flex items-end gap-2">
                <button
                    type="button"
                    wire:click="clearDateFilter"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm
                           hover:bg-gray-50 dark:border-white/20 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    Limpiar
                </button>

                {{-- Botones de exportación (los conectamos en el punto 3) --}}
                <a
                    href="{{ route('dashboard.ventas.export.excel', ['from' => $from_date, 'to' => $to_date]) }}"
                    class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm
                           hover:bg-emerald-500 dark:bg-emerald-500 dark:hover:bg-emerald-400"
                >
                    Exportar Excel
                </a>

                <a
                    href="{{ route('dashboard.ventas.export.pdf', ['from' => $from_date, 'to' => $to_date]) }}"
                    class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm
                           hover:bg-rose-500 dark:bg-rose-500 dark:hover:bg-rose-400"
                >
                    Exportar PDF
                </a>
            </div>
        </div>
    </div>

    {{-- ✅ CARDS DE TOTALES --}}
    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide dark:text-gray-400">
                Total ventas (filtro)
            </p>
            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($totalSales, 2) }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide dark:text-gray-400">
                Ventas en efectivo
            </p>
            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($totalCash, 2) }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide dark:text-gray-400">
                Ventas con débito
            </p>
            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($totalDebit, 2) }}
            </p>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide dark:text-gray-400">
                Ventas con crédito
            </p>
            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                $ {{ number_format($totalCredit, 2) }}
            </p>
        </div>
    </div>

    {{-- aquí siguen tus gráficas como ya las tienes… --}}


    {{-- Gráficas --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Ventas por unidad de negocio --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                Ventas por unidad de negocio
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Total de ventas (efectivo + débito + crédito) por unidad de negocio.
            </p>
            <div class="mt-4" wire:ignore>
                <canvas id="chartByUnit" class="w-full h-64"></canvas>
            </div>
        </div>

        {{-- Ventas por método de pago --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                Ventas por método de pago
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Distribución de ventas por efectivo, tarjeta de débito y tarjeta de crédito.
            </p>
            <div class="mt-4" wire:ignore>
                <canvas id="chartByMethod" class="w-full h-64"></canvas>
            </div>
        </div>
    </div>

    {{-- Script de Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let chartByUnit   = null;
                let chartByMethod = null;

                const ctxUnit   = document.getElementById('chartByUnit')?.getContext('2d');
                const ctxMethod = document.getElementById('chartByMethod')?.getContext('2d');

                if (!ctxUnit || !ctxMethod) {
                    return;
                }

                // Datos iniciales que vienen de Blade
                const initialPayload = {
                    labelsUnits:  @json($labelsUnits ?? []),
                    dataUnits:    @json($dataUnits ?? []),
                    methodLabels: @json($methodLabels ?? []),
                    methodData:   @json($methodData ?? []),
                };

                function normalizePayload(payload) {
                    // Por si Livewire lo envía envuelto como { data: { ... } }
                    if (payload && payload.data) {
                        payload = payload.data;
                    }

                    const labelsUnits  = Array.isArray(payload?.labelsUnits)  ? payload.labelsUnits  : [];
                    const dataUnits    = Array.isArray(payload?.dataUnits)    ? payload.dataUnits    : [];
                    const methodLabels = Array.isArray(payload?.methodLabels) ? payload.methodLabels : [];
                    const methodData   = Array.isArray(payload?.methodData)   ? payload.methodData   : [];

                    return { labelsUnits, dataUnits, methodLabels, methodData };
                }

                function createOrUpdateCharts(rawPayload) {
                    const { labelsUnits, dataUnits, methodLabels, methodData } = normalizePayload(rawPayload);

                    // 🟦 Barras por unidad de negocio
                    if (chartByUnit) {
                        chartByUnit.data.labels = labelsUnits;
                        chartByUnit.data.datasets[0].data = dataUnits;
                        chartByUnit.update();
                    } else {
                        chartByUnit = new Chart(ctxUnit, {
                            type: 'bar',
                            data: {
                                labels: labelsUnits,
                                datasets: [{
                                    label: 'Total vendido',
                                    data: dataUnits,
                                    borderWidth: 1,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: function (ctx) {
                                                const value = ctx.raw ?? 0;
                                                return ' $ ' + value.toLocaleString(undefined, {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function (value) {
                                                return '$ ' + value.toLocaleString();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // 🟣 Dona por método de pago
                    if (chartByMethod) {
                        chartByMethod.data.labels = methodLabels;
                        chartByMethod.data.datasets[0].data = methodData;
                        chartByMethod.update();
                    } else {
                        chartByMethod = new Chart(ctxMethod, {
                            type: 'doughnut',
                            data: {
                                labels: methodLabels,
                                datasets: [{
                                    data: methodData,
                                    borderWidth: 1,
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'bottom' },
                                    tooltip: {
                                        callbacks: {
                                            label: function (ctx) {
                                                const label = ctx.label || '';
                                                const value = ctx.raw ?? 0;
                                                return label + ': $ ' + value.toLocaleString(undefined, {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                });
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                }

                // 1️⃣ Pintar con datos iniciales
                createOrUpdateCharts(initialPayload);

                // 2️⃣ Escuchar actualizaciones desde Livewire
                Livewire.on('chart-data-updated', (event) => {
                    console.log('Evento Livewire chart-data-updated:', event);
                    // event.data contiene lo bueno
                    createOrUpdateCharts(event.data);
                });
            });
        </script>
    @endpush


</div>

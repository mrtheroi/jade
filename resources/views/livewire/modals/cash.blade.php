{{-- Modal de detalle de extracción --}}
@if($showDetailModal && $selectedExtraction)
    <div
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        aria-modal="true"
        role="dialog"
    >
        <div class="w-full max-w-3xl rounded-xl bg-white shadow-xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10">
            {{-- Header modal --}}
            <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Detalle del corte de caja
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Turno {{ $selectedExtraction->turno }} &middot;
                        Fecha operativa: {{ $selectedExtraction->operation_date?->format('d/m/Y') }} &middot;
                        Subido el: {{ $selectedExtraction->created_at?->format('d/m/Y H:i') }}
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="closeDetail"
                    class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>

            <div class="grid gap-6 px-6 py-5 sm:grid-cols-2">
                {{-- Columna izquierda: info general --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Información general
                    </h4>

                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Usuario que subió</dt>
                            <dd class="text-gray-900 dark:text-gray-100 text-right">
                                {{ $selectedExtraction->user?->name ?? 'N/A' }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Turno</dt>
                            <dd class="text-gray-900 dark:text-gray-100 text-right">
                                Turno {{ $selectedExtraction->turno }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Fecha operativa</dt>
                            <dd class="text-gray-900 dark:text-gray-100 text-right">
                                {{ $selectedExtraction->operation_date?->format('d/m/Y') }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Estado</dt>
                            <dd class="text-right">
                                <span class="inline-flex items-center rounded-md
                                             {{ $selectedExtraction->status === 'validado'
                                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50'
                                                : 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/50' }}
                                             px-2 py-1 text-xs font-medium ring-1 ring-inset">
                                    {{ ucfirst($selectedExtraction->status) }}
                                </span>
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Run ID</dt>
                            <dd class="text-[11px] text-gray-900 dark:text-gray-100 text-right">
                                {{ $selectedExtraction->run_id ?? '—' }}
                            </dd>
                        </div>

                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Imagen</dt>
                            <dd class="text-right">
                                @if($selectedExtraction->image_path)
                                    <a href="{{ Storage::disk('public')->url($selectedExtraction->image_path) }}"
                                       target="_blank"
                                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Ver imagen
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">No disponible</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Columna derecha: detalle de montos --}}
                <div class="space-y-4">
                    <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Detalle de montos
                    </h4>

                    {{-- Resumen principal --}}
                    <div class="rounded-lg border border-gray-100 bg-gray-50 p-3 text-sm dark:border-white/10 dark:bg-gray-900/60">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Monto débito</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                $ {{ number_format($selectedExtraction->monto_debito, 2) }}
                            </span>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Monto crédito</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                $ {{ number_format($selectedExtraction->monto_credito, 2) }}
                            </span>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Efectivo</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">
                                $ {{ number_format($selectedExtraction->efectivo, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Tabla detalle ventas/propinas por método de pago --}}
                    <div class="overflow-hidden rounded-lg border border-gray-100 text-xs dark:border-white/10">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                            <thead class="bg-gray-50 dark:bg-gray-950/40">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 dark:text-gray-300">
                                    Método
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-gray-500 dark:text-gray-300">
                                    Ventas
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-gray-500 dark:text-gray-300">
                                    Propinas
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white dark:divide-white/10 dark:bg-gray-900">
                            <tr>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">Efectivo</td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->cash_sales, 2) }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->cash_tips, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">Tarjeta débito</td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->debit_card_sales, 2) }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->debit_card_tips, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">Tarjeta crédito</td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->credit_card_sales, 2) }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->credit_card_tips, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-950/40">
                                <td class="px-3 py-2 text-gray-700 dark:text-gray-200">Totales</td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->total_sales_payment_methods, 2) }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    $ {{ number_format($selectedExtraction->total_tips_payment_methods, 2) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- Footer modal --}}
            {{-- Footer modal --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 px-6 py-3 dark:border-white/10">
                {{-- Botón de validar (solo si aún está procesado) --}}
                @if($selectedExtraction->status !== 'validado')
                    <button
                        type="button"
                        wire:click="markAsValidated"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm
                   hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                   focus-visible:outline-emerald-600"
                    >
                        <svg class="mr-1.5 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Marcar como validado
                    </button>
                @endif

                <button
                    type="button"
                    wire:click="closeDetail"
                    class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endif

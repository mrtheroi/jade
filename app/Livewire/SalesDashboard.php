<?php

namespace App\Livewire;

use App\Models\cash_extractions; // 👈 ajusta al nombre real de tu modelo
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;

class SalesDashboard extends Component
{
    #[Rule('nullable|date')]
    public ?string $from_date = null;

    #[Rule('nullable|date')]
    public ?string $to_date = null;

    public float $totalSales  = 0.0;
    public float $totalCash   = 0.0;
    public float $totalDebit  = 0.0;
    public float $totalCredit = 0.0;

    public function clearDateFilter(): void
    {
        $this->from_date = null;
        $this->to_date   = null;
    }

    /**
     * Construye los datos que necesitan las gráficas
     */
    protected function buildChartData(): array
    {
        $baseQuery = cash_extractions::query()
            // si quieres solo los que están procesados/validados ajusta aquí
            // ->whereIn('status', ['procesado', 'validado']);
        ;

        if ($this->from_date) {
            $baseQuery->whereDate('operation_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $baseQuery->whereDate('operation_date', '<=', $this->to_date);
        }

        // 🔹 1) Totales globales (para cards)
        $totals = (clone $baseQuery)
            ->selectRaw('
                SUM(cash_sales + debit_card_sales + credit_card_sales) as total_sales,
                SUM(cash_sales)        as total_cash,
                SUM(debit_card_sales)  as total_debit,
                SUM(credit_card_sales) as total_credit
            ')
            ->first();

        $this->totalSales  = (float) ($totals->total_sales  ?? 0);
        $this->totalCash   = (float) ($totals->total_cash   ?? 0);
        $this->totalDebit  = (float) ($totals->total_debit  ?? 0);
        $this->totalCredit = (float) ($totals->total_credit ?? 0);

        // 🔹 2) Ventas por unidad de negocio
        $byUnit = (clone $baseQuery)
            ->select(
                'business_unit',
                DB::raw('SUM(cash_sales + debit_card_sales + credit_card_sales) as total_amount')
            )
            ->groupBy('business_unit')
            ->orderBy('business_unit')
            ->get();

        $labelsUnits = $byUnit->pluck('business_unit')->values()->toArray();
        $dataUnits   = $byUnit->pluck('total_amount')->map(fn ($v) => (float) $v)->values()->toArray();

        // 🔹 3) Ventas por método de pago
        $byMethod = (clone $baseQuery)
            ->selectRaw('
                SUM(cash_sales)        as total_cash,
                SUM(debit_card_sales)  as total_debit,
                SUM(credit_card_sales) as total_credit
            ')
            ->first();

        $methodLabels = ['Efectivo', 'Débito', 'Crédito'];
        $methodData   = [
            (float) ($byMethod->total_cash   ?? 0),
            (float) ($byMethod->total_debit  ?? 0),
            (float) ($byMethod->total_credit ?? 0),
        ];

        return [
            'labelsUnits'  => $labelsUnits,
            'dataUnits'    => $dataUnits,
            'methodLabels' => $methodLabels,
            'methodData'   => $methodData,
        ];
    }

    public function render()
    {
        $chartData = $this->buildChartData();

        // Livewire -> JS (Chart.js)
        $this->dispatch('chart-data-updated', data: $chartData);

        return view('livewire.sales-dashboard', $chartData);
    }
}

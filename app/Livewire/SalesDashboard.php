<?php

namespace App\Livewire;

use App\Models\cash_extractions;
use App\Models\CashExtraction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class SalesDashboard extends Component
{
    // Filtros en URL (opcional, pero útil)
    #[Url]
    public ?string $business_unit = null; // null = todas

    #[Url]
    #[Rule('nullable|regex:/^\d{4}-\d{2}$/')]
    public ?string $period_key = null; // YYYY-MM

    #[Url]
    #[Rule('nullable|date')]
    public ?string $from_date = null;

    #[Url]
    #[Rule('nullable|date')]
    public ?string $to_date = null;

    // Cards
    public float $totalSales  = 0.0;
    public float $totalCash   = 0.0;
    public float $totalDebit  = 0.0;
    public float $totalCredit = 0.0;

    public function mount(): void
    {
        // Default: mes actual
        $this->period_key ??= now()->format('Y-m');
        $this->applyPeriodToDates();
    }

    public function updatedPeriodKey(): void
    {
        $this->applyPeriodToDates();
    }

    public function clearFilters(): void
    {
        $this->business_unit = null;
        $this->period_key = now()->format('Y-m');
        $this->applyPeriodToDates();
    }

    public function clearDateFilter(): void
    {
        // mantiene unidad/periodo, solo limpia rango manual
        $this->from_date = null;
        $this->to_date   = null;
    }

    private function applyPeriodToDates(): void
    {
        // Si period_key es válido, se vuelve la “fuente de verdad” del rango
        $pk = $this->period_key;

        if (!$pk || !preg_match('/^\d{4}-\d{2}$/', $pk)) {
            $this->period_key = now()->format('Y-m');
            $pk = $this->period_key;
        }

        $start = \Carbon\Carbon::createFromFormat('Y-m', $pk)->startOfMonth()->toDateString();
        $end   = \Carbon\Carbon::createFromFormat('Y-m', $pk)->endOfMonth()->toDateString();

        $this->from_date = $start;
        $this->to_date   = $end;
    }

    private function baseQuery()
    {
        $q = CashExtraction::query();

        if ($this->business_unit) {
            $q->where('business_unit', $this->business_unit);
        }

        if ($this->from_date) {
            $q->whereDate('operation_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $q->whereDate('operation_date', '<=', $this->to_date);
        }

        return $q;
    }

    protected function buildChartData(): array
    {
        $baseQuery = $this->baseQuery();

        // ✅ Totales globales (cards) con COALESCE para evitar NULL issues
        $totals = (clone $baseQuery)
            ->selectRaw('
                SUM(COALESCE(cash_sales,0) + COALESCE(debit_card_sales,0) + COALESCE(credit_card_sales,0)) as total_sales,
                SUM(COALESCE(cash_sales,0))        as total_cash,
                SUM(COALESCE(debit_card_sales,0))  as total_debit,
                SUM(COALESCE(credit_card_sales,0)) as total_credit
            ')
            ->first();

        $this->totalSales  = (float) ($totals->total_sales  ?? 0);
        $this->totalCash   = (float) ($totals->total_cash   ?? 0);
        $this->totalDebit  = (float) ($totals->total_debit  ?? 0);
        $this->totalCredit = (float) ($totals->total_credit ?? 0);

        // ✅ Ventas por unidad (si el filtro es “todas”, tiene sentido mostrarlo; si no, también sirve como confirmación)
        $byUnit = (clone $baseQuery)
            ->select(
                'business_unit',
                DB::raw('SUM(COALESCE(cash_sales,0) + COALESCE(debit_card_sales,0) + COALESCE(credit_card_sales,0)) as total_amount')
            )
            ->groupBy('business_unit')
            ->orderBy('business_unit')
            ->get();

        $labelsUnits = $byUnit->pluck('business_unit')->values()->toArray();
        $dataUnits   = $byUnit->pluck('total_amount')->map(fn ($v) => (float) $v)->values()->toArray();

        // ✅ Ventas por método
        $byMethod = (clone $baseQuery)
            ->selectRaw('
                SUM(COALESCE(cash_sales,0))        as total_cash,
                SUM(COALESCE(debit_card_sales,0))  as total_debit,
                SUM(COALESCE(credit_card_sales,0)) as total_credit
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

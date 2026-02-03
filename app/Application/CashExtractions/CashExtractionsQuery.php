<?php

namespace App\Application\CashExtractions;

use App\Models\CashExtraction;
use Illuminate\Database\Eloquent\Builder;

class CashExtractionsQuery
{
    /**
     * Construye el query base con los filtros activos.
     *
     * Filtros soportados (todos opcionales):
     * - search: string
     * - business_unit: string
     * - turno: int|string
     * - status: string
     * - date_from: Y-m-d
     * - date_to: Y-m-d
     */
    public function base(array $filters = []): Builder
    {
        $search = trim((string)($filters['search'] ?? ''));

        $q = CashExtraction::query()
            ->with('user');

        // Rango de fechas (si viene)
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $q->whereRaw("date(operation_date) between ? and ?", [
                $filters['date_from'],
                $filters['date_to'],
            ]);
        } else {
            if (!empty($filters['date_from'])) {
                $q->whereRaw("date(operation_date) >= date(?)", [$filters['date_from']]);
            }
            if (!empty($filters['date_to'])) {
                $q->whereRaw("date(operation_date) <= date(?)", [$filters['date_to']]);
            }
        }

        // Filtros específicos
        if (!empty($filters['business_unit'])) {
            $q->where('business_unit', $filters['business_unit']);
        }

        if (!empty($filters['turno'])) {
            $q->where('turno', (int) $filters['turno']);
        }

        if (!empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        // Search
        if ($search !== '') {
            $this->applySearch($q, $search);
        }

        // Orden estable (primero fecha, luego id)
        return $q->orderByDesc('operation_date')
            ->orderByDesc('id');
    }

    /**
     * Totales diarios basados en el MISMO query base (mismos filtros).
     * Nota: Este método no pagina; devuelve agrupado por día.
     */
    public function dailyTotals(Builder $baseQuery)
    {
        return (clone $baseQuery)
            ->selectRaw('operation_date,
                SUM(monto_debito) as total_debito,
                SUM(monto_credito) as total_credito,
                SUM(efectivo) as total_efectivo,
                SUM(total_tips_payment_methods) as total_propina')
            ->groupBy('operation_date')
            ->orderByDesc('operation_date')
            ->get();
    }

    /**
     * Aplica búsqueda a múltiples campos + user.name.
     */
    private function applySearch(Builder $q, string $search): void
    {
        $q->where(function (Builder $qq) use ($search) {
            // Si escriben una fecha exacta, SQLite y MySQL se comportan distinto;
            // esto ayuda cuando el usuario mete "2026-01-30"
            $qq->orWhereDate('operation_date', $search);

            $qq->orWhere('turno', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('run_id', 'like', "%{$search}%")
                ->orWhere('extraction_agent_id', 'like', "%{$search}%");

            $qq->orWhereHas('user', function (Builder $uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }
}

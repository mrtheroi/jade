<?php

namespace App\Http\Controllers;

use App\Models\cash_extractions;
use App\Models\CashExtraction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardExportController extends Controller
{
    protected function queryWithFilters(Request $request)
    {
        $query = CashExtraction::query();

        if ($request->filled('business_unit')) {
            $query->where('business_unit', $request->input('business_unit'));
        }

        if ($request->filled('from')) {
            $query->whereDate('operation_date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('operation_date', '<=', $request->input('to'));
        }

        return $query;
    }

    public function excel(Request $request): StreamedResponse
    {
        $query = $this->queryWithFilters($request);

        $fileName = 'ventas_dashboard_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Fecha operación',
                'Unidad de negocio',
                'Turno',
                'Cash sales',
                'Debit card sales',
                'Credit card sales',
                'Total ventas (cash+debit+credit)',
            ]);

            $query->orderBy('operation_date')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        $total = ($row->cash_sales ?? 0)
                            + ($row->debit_card_sales ?? 0)
                            + ($row->credit_card_sales ?? 0);

                        fputcsv($handle, [
                            optional($row->operation_date)->format('Y-m-d'),
                            $row->business_unit,
                            $row->turno,
                            $row->cash_sales,
                            $row->debit_card_sales,
                            $row->credit_card_sales,
                            $total,
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }

    public function pdf(Request $request)
    {
        $rows = $this->queryWithFilters($request)
            ->orderBy('operation_date')
            ->get();

        $totalSales = $rows->sum(fn ($row) =>
            ($row->cash_sales ?? 0)
            + ($row->debit_card_sales ?? 0)
            + ($row->credit_card_sales ?? 0)
        );

        $pdf = \PDF::loadView('exports.dashboard-ventas-pdf', [
            'rows'         => $rows,
            'totalSales'   => $totalSales,
            'from'         => $request->input('from'),
            'to'           => $request->input('to'),
            'businessUnit' => $request->input('business_unit'),
        ])->setPaper('a4', 'portrait');

        $fileName = 'ventas_dashboard_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}

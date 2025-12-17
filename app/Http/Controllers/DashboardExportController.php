<?php

namespace App\Http\Controllers;

use App\Models\cash_extractions;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardExportController extends Controller
{
    protected function queryWithFilters(Request $request)
    {
        $query = cash_extractions::query();

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

            // BOM para que Excel detecte UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
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
        // Para PDF lo ideal es usar barryvdh/laravel-dompdf:
        // composer require barryvdh/laravel-dompdf
        //
        // Aquí te dejo un ejemplo básico:
        $rows = $this->queryWithFilters($request)
            ->orderBy('operation_date')
            ->get();

        $totalSales = $rows->sum(function ($row) {
            return ($row->cash_sales ?? 0)
                + ($row->debit_card_sales ?? 0)
                + ($row->credit_card_sales ?? 0);
        });

        // Vista que vamos a imprimir en PDF
        $pdf = \PDF::loadView('exports.dashboard-ventas-pdf', [
            'rows'       => $rows,
            'totalSales' => $totalSales,
            'from'       => $request->input('from'),
            'to'         => $request->input('to'),
        ])->setPaper('a4', 'portrait');

        $fileName = 'ventas_dashboard_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($fileName);
    }
}

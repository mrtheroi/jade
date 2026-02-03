<?php

namespace App\Application\CashExtractions;

use App\Models\CashExtraction;
use App\Services\ExtractService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SubmitCashExtraction
{
    public function __construct(private readonly ExtractService $extractService) {}

    public function handle(
        UploadedFile $file,
        string $operationDate,
        int $turno,
        string $businessUnit,
        ?string $validationResult = 'cuadro',
        ?string $validationNote = null,
        ?int $userId = null,
    ): CashExtraction {
        $response = $this->extractService->extraction($file);

        if (! $response->successful()) {
            throw new RuntimeException('Ocurrió un error al enviar la imagen al procesador externo.');
        }

        $json = $response->json();

        $data  = $json['data'] ?? [];
        $sales = $data['sales_payment_methods'] ?? [];
        $tips  = $data['tips_payment_methods'] ?? [];

        $cashSales       = (float) ($sales['cash_sales'] ?? 0);
        $debitCardSales  = (float) ($sales['debit_card_sales'] ?? 0);
        $creditCardSales = (float) ($sales['credit_card_sales'] ?? 0);
        $creditSales     = (float) ($sales['credit_sales'] ?? 0);
        $totalSales      = (float) ($sales['total_sales_payment_methods'] ?? 0);

        $cashTips        = (float) ($tips['cash_tips'] ?? 0);
        $debitCardTips   = (float) ($tips['debit_card_tips'] ?? 0);
        $creditCardTips  = (float) ($tips['credit_card_tips'] ?? 0);
        $totalTips       = (float) ($tips['total_tips_payment_methods'] ?? 0);

        $montoDebito  = $debitCardSales + $debitCardTips;
        $montoCredito = $creditCardSales + $creditCardTips;
        $efectivo     = $cashSales + $cashTips;

        $path = $file->store('cortes_caja', 'public');

        try {
            return DB::transaction(function () use (
                $userId,
                $turno,
                $operationDate,
                $path,
                $file,
                $businessUnit,
                $cashSales,
                $debitCardSales,
                $creditCardSales,
                $creditSales,
                $totalSales,
                $cashTips,
                $debitCardTips,
                $creditCardTips,
                $totalTips,
                $montoDebito,
                $montoCredito,
                $efectivo,
                $json,
                $validationResult,
                $validationNote,
            ) {
                return CashExtraction::create([
                    'user_id'             => $userId,
                    'turno'               => $turno,
                    'operation_date'      => Carbon::parse($operationDate)->toDateString(),

                    'image_path'          => $path,
                    'image_original_name' => $file->getClientOriginalName(),

                    'business_unit'       => $businessUnit,

                    'cash_sales'          => $cashSales,
                    'debit_card_sales'    => $debitCardSales,
                    'credit_card_sales'   => $creditCardSales,
                    'credit_sales'        => $creditSales,
                    'total_sales_payment_methods' => $totalSales,

                    'cash_tips'           => $cashTips,
                    'debit_card_tips'     => $debitCardTips,
                    'credit_card_tips'    => $creditCardTips,
                    'total_tips_payment_methods'  => $totalTips,

                    'monto_debito'        => $montoDebito,
                    'monto_credito'       => $montoCredito,
                    'efectivo'            => $efectivo,

                    'run_id'              => $json['run_id'] ?? null,
                    'extraction_agent_id' => $json['extraction_agent_id'] ?? null,
                    'extraction_metadata' => $json['extraction_metadata'] ?? null,

                    'status'              => 'procesado',
                    'cash_validation_result' => $validationResult,
                    'cash_validation_note'   => $validationNote,
                ]);
            });
        } catch (\Throwable $e) {
            // Si falló DB, borramos el archivo para no dejar huérfanos
            Storage::disk('public')->delete($path);
            throw $e;
        }
    }
}

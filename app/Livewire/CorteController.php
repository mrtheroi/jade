<?php

namespace App\Livewire;

use App\Models\cash_extractions;
use App\Services\ExtractService;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class CorteController extends Component
{
    use WithFileUploads;

    #[Url]
    public $search = '';

    public $open = false;
    public $sort = 'id';
    public $direction = 'DESC';

    #[Rule('required|date')]
    public $date;

    #[Rule('required|in:1,2')]
    public $turno = '';

    #[Rule('required|in:Jade,Fuego Ambar,KIN')]
    public $business_unit;

    #[Rule('required|image|mimes:jpg,jpeg,png|max:20480')]
    public $file;

    public ?cash_extractions $selectedExtraction = null;
    public bool $showDetailModal = false;

    public function showDetail(int $id): void
    {
        $this->selectedExtraction = cash_extractions::with('user')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
    }

    public function updatingSearch(): void
    {
        // Para que al cambiar el buscador regrese a la página 1
        $this->resetPage();
    }

    public function submit(ExtractService $extractService)
    {
        $validated = $this->validate();
        $response = $extractService->extraction($this->file);

        if (! $response->successful()) {
            $this->addError('file', 'Ocurrió un error al enviar la imagen al procesador externo.');
            return;
        }

        $json  = $response->json();
        $data  = $json['data'] ?? [];

        $sales = $data['sales_payment_methods'] ?? [];
        $tips  = $data['tips_payment_methods'] ?? [];

        $cashSales        = (float)($sales['cash_sales'] ?? 0);
        $debitCardSales   = (float)($sales['debit_card_sales'] ?? 0);
        $creditCardSales  = (float)($sales['credit_card_sales'] ?? 0);
        $creditSales      = (float)($sales['credit_sales'] ?? 0);
        $totalSales       = (float)($sales['total_sales_payment_methods'] ?? 0);

        $cashTips         = (float)($tips['cash_tips'] ?? 0);
        $debitCardTips    = (float)($tips['debit_card_tips'] ?? 0);
        $creditCardTips   = (float)($tips['credit_card_tips'] ?? 0);
        $totalTips        = (float)($tips['total_tips_payment_methods'] ?? 0);

        $montoDebito   = $debitCardSales + $debitCardTips;
        $montoCredito  = $creditCardSales + $creditCardTips;
        $efectivo      = $cashSales + $cashTips;

        $path = $this->file->store('cortes_caja', 'public');

        $extraction = cash_extractions::create([
            'user_id'                    => auth()->id(),
            'turno'                      => (int)$this->turno,
            'operation_date'             => $this->date,
            'image_path'                 => $path,
            'image_original_name'        => $this->file->getClientOriginalName(),

            'business_unit'              => $this->business_unit,
            'cash_sales'                 => $cashSales,
            'debit_card_sales'           => $debitCardSales,
            'credit_card_sales'          => $creditCardSales,
            'credit_sales'               => $creditSales,
            'total_sales_payment_methods'=> $totalSales,

            'cash_tips'                  => $cashTips,
            'debit_card_tips'            => $debitCardTips,
            'credit_card_tips'           => $creditCardTips,
            'total_tips_payment_methods' => $totalTips,

            'monto_debito'               => $montoDebito,
            'monto_credito'              => $montoCredito,
            'efectivo'                   => $efectivo,

            'run_id'                     => $json['run_id'] ?? null,
            'extraction_agent_id'        => $json['extraction_agent_id'] ?? null,
            'extraction_metadata'        => $json['extraction_metadata'] ?? null,
            'status'                     => 'procesado',
        ]);

        $this->reset(['file', 'turno', 'date']);
        $this->dispatch('notify', message: 'Registro creado con éxito', type: 'success');
    }

    public function markAsValidated(): void
    {
        if (! $this->selectedExtraction) {
            return;
        }

        // Si ya está validado, no hacemos nada
        if ($this->selectedExtraction->status === 'validado') {
            return;
        }

        // Actualizar en BD
        $this->selectedExtraction->update([
            'status' => 'validado',
        ]);

        // Refrescar el modelo en memoria
        $this->selectedExtraction->refresh();

        // (Opcional) podrías disparar un toast/notificación
        $this->dispatch('notify', message: 'Corte validado correctamente.', type: 'success');
    }


    public function render()
    {
        $baseQuery = cash_extractions::with('user')
            ->latest(); // created_at DESC

        if ($this->search) {
            $search = $this->search;

            $baseQuery->where(function ($q) use ($search) {
                $q->where('turno', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('run_id', 'like', "%{$search}%")
                    ->orWhereDate('operation_date', $search)
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 👇 Clonamos el query para usarlo en el resumen diario
        $dailyTotals = (clone $baseQuery)
            ->selectRaw('operation_date,
                         SUM(monto_debito)   as total_debito,
                         SUM(monto_credito)  as total_credito,
                         SUM(efectivo)       as total_efectivo')
            ->groupBy('operation_date')
            ->orderByDesc('operation_date')
            ->get();

        // Lista paginada para la tabla principal
        $extractions = $baseQuery->paginate(10);

        return view('livewire.corte-controller', [
            'extractions'  => $extractions,
            'dailyTotals'  => $dailyTotals,
        ]);
    }
}

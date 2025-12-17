<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Supply;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliesController extends Component
{
    use WithPagination;

    // Buscador sincronizado con la URL
    #[Url]
    public string $search = '';

    #[Rule('nullable|date')]
    public ?string $from_date = null;

    #[Rule('nullable|date')]
    public ?string $to_date = null;

    // Control del modal
    public bool $open = false;

    public bool $showDetailModal = false;
    public ?Supply $detailSupply = null;

    // Id del registro actual (para editar)
    public ?int $supplyId = null;

    // Campos del formulario
    #[Rule('required|exists:categories,id')]
    public $category_id = '';

    #[Rule('required|numeric|min:0')]
    public $amount = '';

    #[Rule('nullable|in:efectivo,transferencia,tarjeta_credito,tarjeta_debito,cheque,otro')]
    public ?string $payment_type = null;

    #[Rule('nullable|date')]
    public ?string $payment_date = null;

    #[Rule('required|in:pendiente,pagado,cancelado')]
    public ?string $status = 'pendiente';

    #[Rule('nullable|string|max:1000')]
    public ?string $notes = null;

    // Para confirmar eliminación
    public ?int $deleteId = null;

    // Al cambiar buscador, volver a página 1
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function showDetail(int $id): void
    {
        $this->detailSupply = Supply::with('category')->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->detailSupply = null;
    }

    // Abrir modal en modo "crear"
    public function create(): void
    {
        $this->resetForm();
        $this->open = true;
    }

    public function clearDateFilter(): void
    {
        $this->from_date = null;
        $this->to_date   = null;
        $this->resetPage();
    }

    // Abrir modal en modo "editar"
    public function edit(int $id): void
    {
        $supply = Supply::findOrFail($id);

        $this->supplyId     = $supply->id;
        $this->category_id  = $supply->category_id;
        $this->amount       = $supply->amount;
        $this->payment_type = $supply->payment_type;
        $this->payment_date = $supply->payment_date?->format('Y-m-d');
        $this->status       = $supply->status;
        $this->notes        = $supply->notes;

        $this->open = true;
    }

    // Cerrar modal
    public function closeModal(): void
    {
        $this->open = false;
        $this->resetValidation();
    }

    // Guardar (create / update)
    public function save(): void
    {
        $validated = $this->validate();

        // Si quieres también calcular payment_month de forma automática:
        if (!empty($validated['payment_date'])) {
            $date = \Carbon\Carbon::parse($validated['payment_date']);
            $validated['payment_month'] = $date->format('Y-m'); // Ej: 2025-02
        }

        Supply::updateOrCreate(
            ['id' => $this->supplyId],
            $validated,
        );

        $this->dispatch('notify', message: 'El registro se guardo correctamente.',type: 'success');

        $this->closeModal();
        $this->resetForm();
    }

    // Preparar eliminación
    public function deleteConfirmation($id): void
    {
        $this->dispatch('showConfirmationModal', userId: $id)->to(ConfirmModal::class);

    }

    #[On('deleteConfirmed')]
    public function destroy($id): void
    {
        if ($this->deleteId) {
            Supply::where('id', $this->deleteId)->delete();
            $this->deleteId = null;
        }
        $this->dispatch('notify', message: 'La categoria se elimino con éxito', type: 'success');
    }

    // Resetear campos de formulario
    protected function resetForm(): void
    {
        $this->supplyId     = null;
        $this->category_id  = '';
        $this->amount       = '';
        $this->payment_type = null;
        $this->payment_date = null;
        $this->status       = 'pendiente';
        $this->notes        = null;
    }

    public function render()
    {
        // 👉 Query base de la tabla
        $baseQuery = Supply::with('category')
            ->orderByDesc('payment_date')
            ->orderByDesc('id');

        // 🔹 Filtro por texto (search)
        if (trim($this->search) !== '') {
            $search = trim($this->search);

            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('category', function ($cq) use ($search) {
                    $cq->where('business_unit', 'like', "%{$search}%")
                        ->orWhere('expense_name', 'like', "%{$search}%")
                        ->orWhere('provider_name', 'like', "%{$search}%");
                })
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('payment_type', 'like', "%{$search}%");
            });
        }

        // 🔹 Filtro por rango de fechas (se aplica tanto a tabla como a cards)
        if ($this->from_date) {
            $baseQuery->whereDate('payment_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $baseQuery->whereDate('payment_date', '<=', $this->to_date);
        }

        // 👉 Totales por unidad de negocio para las CARDS
        $totalsQuery = Supply::query()
            ->join('categories', 'supplies.category_id', '=', 'categories.id')
            ->selectRaw('categories.business_unit as business_unit, SUM(supplies.amount) as total_amount')
            ->groupBy('categories.business_unit')
            ->orderBy('categories.business_unit');

        // 🔹 Mismo filtro de fechas para las cards
        if ($this->from_date) {
            $totalsQuery->whereDate('supplies.payment_date', '>=', $this->from_date);
        }

        if ($this->to_date) {
            $totalsQuery->whereDate('supplies.payment_date', '<=', $this->to_date);
        }

        $totalsByUnit = $totalsQuery->get();

        return view('livewire.supplies-controller', [
            'supplies'     => $baseQuery->paginate(10),
            'categories'   => Category::orderBy('business_unit')
                ->orderBy('expense_name')
                ->orderBy('provider_name')
                ->get(),
            'totalsByUnit' => $totalsByUnit,
        ]);
    }
}

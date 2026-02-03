<?php

namespace App\Livewire;

use App\Application\CashExtractions\CashExtractionsQuery;
use App\Application\CashExtractions\SubmitCashExtraction;
use App\Application\CashExtractions\ValidateCashExtraction;
use App\Domain\BusinessUnit;
use App\Models\CashExtraction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CorteController extends Component
{
    use WithFileUploads, WithPagination;

    #[Url]
    public string $search = '';

    public bool $open = false;

    public ?string $date = null;
    public string $turno = '';
    public string $business_unit = '';
    public $file;

    public ?CashExtraction $selectedExtraction = null;
    public bool $showDetailModal = false;

    public string $validation_result = 'cuadro';
    public ?string $validation_note = null;

    public ?string $filter_business_unit = null;
    public ?string $filter_turno = null;   // o ?int
    public ?string $filter_status = null;

    public ?string $date_from = null;
    public ?string $date_to = null;

    public function updatedFilterBusinessUnit(): void { $this->resetPage(); }
    public function updatedFilterTurno(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }
    public function updatedDateFrom(): void { $this->resetPage(); }
    public function updatedDateTo(): void { $this->resetPage(); }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'turno' => ['required', 'in:1,2'],
            'business_unit' => ['required', 'in:' . implode(',', BusinessUnit::values())],
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:20480'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function submit(SubmitCashExtraction $useCase): void
    {
        $this->validate();

        try {
            $useCase->handle(
                file: $this->file,
                operationDate: $this->date,
                turno: (int) $this->turno,
                businessUnit: $this->business_unit,
                validationResult: $this->validation_result,
                validationNote: $this->validation_note,
                userId: auth()->id(),
            );

            $this->reset(['file', 'turno', 'date']);
            $this->dispatch('notify', message: 'Registro creado con éxito', type: 'success');
        } catch (\Throwable $e) {
            $this->addError('file', $e->getMessage());
        }
    }

    public function showDetail(int $id): void
    {
        $this->selectedExtraction = CashExtraction::with('user')->findOrFail($id);
        $this->validation_result = $this->selectedExtraction->cash_validation_result ?? 'cuadro';
        $this->validation_note   = $this->selectedExtraction->cash_validation_note ?? null;
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
    }

    public function validateCashExtraction(ValidateCashExtraction $useCase): void
    {
        if (! $this->selectedExtraction) return;

        try {
            $this->selectedExtraction = $useCase->handle(
                id: $this->selectedExtraction->id,
                result: $this->validation_result,
                note: $this->validation_note
            );

            $this->dispatch('notify', message: 'Corte validado: ' . strtoupper($this->validation_result), type: 'success');
        } catch (\Throwable $e) {
            $this->addError('validation_note', $e->getMessage());
        }
    }

    private function normalizeDate(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') return null;

        // Si ya viene YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Si viene DD/MM/YYYY
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        // Último intento: parse general
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    public function render(CashExtractionsQuery $query)
    {
        $dateFrom = $this->normalizeDate($this->date_from) ?? now()->subDays(30)->toDateString();
        $dateTo   = $this->normalizeDate($this->date_to) ?? now()->toDateString();

        $filters = [
            'search' => $this->search,
            'business_unit' => $this->filter_business_unit ?: null,
            'turno' => $this->filter_turno ?: null,
            'status' => $this->filter_status ?: null,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ];

        $baseQuery = $query->base($filters);

        $dailyTotals = $query->dailyTotals($baseQuery);
        $extractions = $baseQuery->paginate(10);

        return view('livewire.corte-controller', compact('extractions', 'dailyTotals'));
    }

    public function applySubmitAsFilter(): void
    {
        if ($this->date) {
            $this->date_from = $this->date;
            $this->date_to   = $this->date;
        }

        if ($this->turno !== '') {
            $this->filter_turno = $this->turno;
        }

        if ($this->business_unit !== '') {
            $this->filter_business_unit = $this->business_unit;
        }

        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filter_business_unit = null;
        $this->filter_turno = null;
        $this->filter_status = null;

        $this->date_from = now()->subDays(30)->toDateString();
        $this->date_to   = now()->toDateString();

        $this->resetPage();
    }
}
